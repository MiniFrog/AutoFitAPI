<?php

namespace App\Http\Controllers;

use App\AutoFitAPI;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Uuid;

class Administration extends Controller
{
    public function console()
    {
        return view("welcome")->with([
            "APINames" => $this->autoFitAPIs(),
            "runningAPIs" => $this->runningAPIAbstracts()
        ]);
    }

    public function description($APIName, $version = null)
    {
        $versions = $this->APIVersions($APIName);
        $version = $version ? $version : $versions[count($versions) - 1];
        $detail = $this->decodeDetailArray($this->APIDetailsArray($APIName, $version), "");
        return view("description", [
            "APINames" => $this->autoFitAPIs(),
            "currentAPI" => $APIName,
            "currentVersion" => $version,
            "versions" => $versions,
            "detail" => $detail,
        ]);
    }

    public function create($APIName, $version = null)
    {
        $versions = $this->APIVersions($APIName);
        $version = $version ? $version : $versions[count($versions) - 1];
        $decoded = $this->decodedDetail($APIName, $version);
        return view("create", [
            "APINames" => $this->autoFitAPIs(),
            "currentAPI" => $APIName,
            "currentVersion" => $version,
            "versions" => $versions,
            "detail" => $decoded,
            "detailJSArray" => $this->decodedDetailToJSArray($decoded)
        ]);
    }

    public function reprocess(Request $request, $APIName, $version)
    {
        $index = 0;
        $restrictions = [];
        $detailArray = json_decode($request->input("detail", []), true);
        $this->detailToColumnRestrictions(
            $detailArray,
            $index,
            $restrictions,
            ""
        );
        AutoFitAPI::create([
            "restrictions" => json_encode($restrictions),
            "version" => $version,
            "uuid" => Uuid::generate()->string,
            "code" => md5(time()),
            "is_running" => true,
            "api_name" => $APIName
        ]);
        return redirect("/");
    }

    /**
     * @param $detail
     * @param $index
     * @param $restrictions
     * @param $columnPrefix
     * @return void
     */
    private function detailToColumnRestrictions(&$detail, &$index, &$restrictions, $columnPrefix)
    {
        for (; $index < count($detail); $index++) {

            // ignore the tail and continue
            $layer = $detail[$index]["layer"];
            if ($layer == "tail") {
                return;
            }

            // create some temp variable for convenience
            $info = $detail[$index]["info"];
            $path = explode(".", $detail[$index]["id"]);
            $currentId = end($path);
            $info["name"] = $columnPrefix === "" ? $currentId : $columnPrefix . "." . $currentId;
            $name = $info["name"];

            // create new restriction index in the array restrictions
            $restrictions[$name] = [];

            // analyze the head
            if ($layer == "head") {
                $this->autoGenerateExistence($restrictions[$name], $info);
                $this->autoGenerateType($restrictions[$name], $info);
                $this->autoGenerateSize($restrictions[$name], $info);
                $index++;
                if ($this->hasSize($info)) {
                    $this->detailToColumnRestrictions(
                        $detail,
                        $index,
                        $restrictions,
                        $name . ".*"
                    );
                } else {
                    $this->detailToColumnRestrictions(
                        $detail,
                        $index,
                        $restrictions,
                        $name
                    );
                }
            }

            // analyze content
            if ($layer == "content") {
                $this->autoGenerateExistence($restrictions[$name], $info);
                $this->autoGenerateType($restrictions[$name], $info);
                $this->autoGenerateSize($restrictions[$name], $info);
                $this->autoGenerateLength($restrictions, $info);
                $this->autoGenerateExtra($restrictions[$name], $info);
            }
        }
    }

    /**
     * @param $restriction array
     * @param $info array
     */
    private function autoGenerateExistence(&$restriction, &$info)
    {
        $size = array_get($info["restrictions"], "size", null);
        $length = array_get($info["restrictions"], "length", null);
        $exist = array_get($info["restrictions"], "exist", null);
        if ($size !== null && is_array($size) && $size["min"] == 0) {
            // 有size说明的情况下且size含有最小值，且最小值为0则可以推测为nullable
            $restriction[] = "nullable";
        } else if ($length !== null && is_array($length) && $length["min"] == 0) {
            // 有长度说明，且长度最小值为0可以推测为nullable
            $restriction[] = "nullable";
        } else if ($exist !== null) {
            // 在有显式exist说明的时候，使用显示说明的要求
            $restriction[] = $exist;
        } else {
            // 默认采用required
            $restriction[] = "required";
        }
    }

    /**
     * @param $restriction array
     * @param $info array
     */
    private function autoGenerateSize(&$restriction, &$info)
    {
        $size = array_get($info["restrictions"], "size", null);
        if ($size === null)
            return;
        if (is_array($size)) {
            $restriction[] = "max:" . $size["max"];
            $restriction[] = "min:" . $size["min"];
        } else {
            $restriction[] = "size:" . $size;
        }
    }

    /**
     * @param $restrictions array
     * @param $info array
     */
    private function autoGenerateLength(&$restrictions, &$info)
    {
        $name = $info["name"];
        $length = array_get($info["restrictions"], "length", null);
        if ($length === null) {
            return;
        }
        if ($this->hasSize($info)) {
            if (is_array($length)) {
                $restrictions[$name . ".*"][] = "min:{$length["min"]}";
                $restrictions[$name . ".*"][] = "max:{$length["max"]}";
            } else {
                $restrictions[$name . ".*"][] = "size:$length";
            }
        } else {
            if (is_array($length)) {
                $restrictions[$name][] = "min:{$length["min"]}";
                $restrictions[$name][] = "max:{$length["max"]}";
            } else {
                $restrictions[$name][] = "size:$length";
            }
        }
    }

    /**
     * @param $restriction array
     * @param $info array
     */
    private function autoGenerateExtra(&$restriction, &$info)
    {
        $extra = array_get($info["restrictions"], "extra", null);
        if ($extra === null) {
            return;
        }
        foreach ($extra as $piece) {
            $restriction[] = $piece;
        }
    }

    /**
     * @param $info array
     * @return array | string | null
     */
    private function getSize($info)
    {
        return array_get($info["restrictions"], "size", null);
    }

    /**
     * @param $info array
     * @return bool
     */
    private function hasSize($info)
    {
        return array_get($info["restrictions"], "size", null) !== null;
    }

    /**
     * @param $restriction array
     * @param $info array
     */
    private function autoGenerateType(&$restriction, &$info)
    {
        $type = array_get($info["restrictions"], "type", null);
        if ($type !== null) {
            $restriction[] = $type;
        }
    }

    /**
     * @return array all api names
     */
    private function autoFitAPIs()
    {
        $APIs = [];
        foreach (config('autoFitAPI') as $key => $value) {
            $APIs[$key] = $value["name"];
        }
        return $APIs;
    }

    /**
     * @return array all the running APIs
     */
    private function runningAPIAbstracts()
    {
        $runningAPIs = AutoFitAPI::where("is_running", "=", true)
            ->orderBy("updated_at")
            ->get([
                "uuid",
                "code",
                "api_name as APIName",
                "version",
                "created_at as createdAt",
                "updated_at as updatedAt"
            ]);
        return $runningAPIs;
    }

    /**
     * @param $APIName string
     * @param $version string
     * @return array | null when api not exist
     */
    private function APIDetailsArray($APIName, $version)
    {
        return $this->APIDetailsRawToArray($this->APIDetailsRaw($APIName, $version));
    }

    /**
     * @param $APIName string
     * @param $version string
     * @return null|string
     */
    private function APIDetailsRaw($APIName, $version)
    {
        try {
            return Storage::disk("api")->get("/$APIName/$version.json");
        } catch (FileNotFoundException $e) {
            return null;
        }
    }


    /**
     * @param $APIDetailRaw string
     * @return array
     */
    private function APIDetailsRawToArray($APIDetailRaw)
    {
        if ($APIDetailRaw == null) {
            return [];
        }
        return json_decode($APIDetailRaw, true)[0];
    }


    /**
     * @param $APIName string
     * @return array | null
     */
    private function APIVersions($APIName)
    {
        return config("autoFitAPI.$APIName.versions");
    }

    /**
     * @param $APIName string
     * @param $version string
     * @return array | null
     */
    private function decodedDetail($APIName, $version)
    {
        return $this->decodeDetailArray($this->APIDetailsArray($APIName, $version), "");
    }

    /**
     * @param $detailArray array
     * @param $context string
     * @return array
     */
    private function decodeDetailArray($detailArray, $context)
    {
        $result = [];
        foreach ($detailArray as $name => $column) {
            if (key_exists("fields", $column)) {
                $result[] = ["layer" => "head", "id" => $context . $name, "info" => array_diff_key($column, ["fields" => "fields"])];
                $result = array_merge($result, $this->decodeDetailArray($column["fields"], $context . $name . "."));
                $result[] = ["layer" => "tail"];
            } else {
                $result[] = ["layer" => "content", "id" => $context . $name, "info" => $column];
            }
        }
        return $result;
    }

    /**
     * @param $decodedDetail array
     * @return string
     */
    private function decodedDetailToJSArray($decodedDetail)
    {
        return json_encode($decodedDetail);
    }
}
