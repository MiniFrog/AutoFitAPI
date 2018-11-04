<?php

namespace App\Http\Controllers;

use App\AutoFitAPI as MAutoFitAPI;
use App\Container;
use App\Exports\AutoFitAPIExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Uuid;

class AutoFitAPI extends Controller
{
    public function store(Request $request, $code)
    {
        $rules = $this->findRules($code);
        if ($rules === null) {
            return Response("", 404);
        }
        $request->validate($rules);
        $input = $request->all();
/*        $index = 0;
        $remains = [];
        $keys = array_keys($input);
        $this->filter($input, $keys, $remains, $index, "");*/
        $uuid = Uuid::generate()->string;
        Container::create([
            "code" => $code,
            "uuid" => $uuid,
            "content" => json_encode($input, JSON_UNESCAPED_UNICODE)
        ]);
        return ["uuid" => $uuid];
    }

    public function export($code) {
        $api = MAutoFitAPI::where("code", "=", $code)->first(["uuid", "updated_at"]);
        if ($api == null)
            return [];
        $contents = Container::where("code", "=", $code)->get(["content"]);
        $data = [];
        foreach ($contents as $content) {
            $content = $content->content;
            $content = preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $content);
            $data[] = json_decode($content, true);
        }
        return Excel::download(new AutoFitAPIExport($api->uuid, $data), $api->updated_at . ".xls");
    }


    /**
     * 根据rules，去除无关的字段，现在还没有实现
     * 这个等下一版本吧或者什么时候有空了
     * @param $input array
     * @param $keys array
     * @param $remains array
     * @param $index int
     * @param $parent string
     * @return bool
     */
    private function filter(&$input, &$keys, &$remains, &$index, $parent)
    {
        for (; $index < count($keys); $index++) {
            $key = $keys[$index];
            if ($parent == "") {
                $index += 1;
                if (!$this->filter($input, $keys, $remains, $index, $key)) {
                    $this->put($key, $input, $remains);
                }
            } else {
                if (strpos($key, $parent) !== false) {
                    $index += 1;
                    if (! $this->filter($input, $keys, $remains, $index, $keys[$index - 1])) {
                        $this->put($key, $input, $remains);
                    }
                    return true;
                }
                return false;

            }
        }
    }

    private function put($key, &$input, &$remains)
    {
        $subRemains = &$remains;
        $subInput = &$input;
        while (true) {
            $subKey = strstr($key, ".", true);
            if ($subKey == false) {
                $subRemains[$key] = $subInput[$key];
                return;
            }
            $key = substr($key, strlen($subKey) + 1);
            if ($subKey == "*") {
                foreach ($subInput as &$subInputArrayItem) {
                    $subRemains[] = [];
                    $subRemainsArrayChild = &end($subRemains);
                    $this->put($key, $subInputArrayItem, $subRemainsArrayChild);
                    return;
                }
            } else {
                if (!key_exists($subKey, $subRemains)) {
                    $subRemains[$subKey] = [];
                }
                $subRemains = &$subRemains[$subKey];
                $subInput = &$subInput[$subKey];
            }
        }

    }

    /**
     * @param $code string md5 API code
     * @return array | null on specified api not find
     */
    protected function findRules($code)
    {
        $model = MAutoFitAPI::where("code", "=", $code)
            ->where("is_running", "=", true)
            ->first();
        if ($model !== null) {
            return json_decode($model->restrictions, true);
        }
        return null;
    }

    /**
     * 返回数组中指定多列
     *
     * @param  array $array 需要取出数组列的一维数组
     * @param  array $column_keys 要取出的列名
     * @return array
     */
    protected function array_columns_simple($array, $column_keys = null)
    {
        return array_intersect_key($array, array_combine($column_keys, $column_keys));
    }

    /**
     * 返回数组中指定多列
     *
     * @param  array $arrays 需要取出数组列的多维数组
     * @param  array $column_keys 要取出的列名
     * @return array
     */
    protected function array_columns_multiple($arrays, $column_keys = null)
    {
        $key_to_key = array_combine($column_keys, $column_keys);
        $result = [];
        foreach ($arrays as $array) {
            $result[] = array_intersect_key($array, $key_to_key);
        }
        return $result;
    }
}
