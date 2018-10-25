<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class APIHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:helper {apiName : 接口名}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '帮助你选择该次报名表的字段，注意所有可选的字段都存放在{laravel}/config/register.php中';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api = $this->argument('apiName');
        if (!in_array($api, config('register.functions'))) {
            $this->info("接口{$api}不存在。");
            return;
        }
        $continue = $this->ask("该操作将会删除原有数据表，确认要继续吗？Y/N");
        if ($continue != "Y") {
            $this->info("操作终止");
            return;
        }
        $availableColumns = config("register.{$api}Options");
        $selectedColumns = $this->select($availableColumns);
        $this->conclusion($selectedColumns);

        // 进行确认
        $confirm = $this->ask("确认无误 Y/N : ");
        if ($confirm != "Y") {
            return;
        }

        // 把配置写入json文件
        Storage::disk("api")->put($api . '.json', json_encode(array_keys($selectedColumns)));
    }


    private function select($columns)
    {
        $selectedColumns = [];
        foreach ($columns as $key => $column) {
            if ($key == "required") {
                $selectedColumns = array_merge($this->required($column), $selectedColumns);
            } else if ($key == "unique") {
                $selectedColumns = array_merge($this->unique($column), $selectedColumns);
            } else {
                $addOrNot = $this->ask("字段 : $column Y/N");
                if ($addOrNot == "Y") {
                    $selectedColumns[$key] = $column;
                }
            }
        }
        return $selectedColumns;
    }

    private function required($columns)
    {
        $selectedColumns = [];
        foreach ($columns as $key => $column) {
            if ($key != "unique") {
                $this->info("字段 ： \"$column\" -> 必选");
                $selectedColumns[$key] = $column;
            } else {
                $selectedColumns = array_merge($this->requiredUnique($column), $selectedColumns);
            }
        }
        return $selectedColumns;
    }

    private function unique($columns)
    {
        $selectedColumns = [];
        foreach ($columns as $column) {
            $this->info("以下字段为多选一：");
            $i = 1;
            foreach ($column as $option) {
                $this->info("   {$i}. {$option}");
                $i++;
            }
            $askString = "";
            for ($i = 1; $i < count($column) + 1; $i++) {
                $askString .= "$i / ";
            }
            $askString .= "N:";
            $addOrNot = $this->ask($askString);
            if ($addOrNot > 0 && $addOrNot < count($column) + 1 && strlen($addOrNot) == 1) {
                $key = array_keys($column)[$addOrNot - 1];
                $selectedColumns[$key] = $column[$key];
            }
        }
        return $selectedColumns;
    }

    private function requiredUnique($columns)
    {
        $selectedColumns = [];
        foreach ($columns as $column) {
            $this->info("以下字段为多选一：");
            $i = 1;
            foreach ($column as $option) {
                $this->info("   {$i}. {$option}");
                $i++;
            }
            $askString = "";
            for ($i = 1; $i < count($column) + 1; $i++) {
                $askString .= "$i / ";
            }
            while (true) {
                $addOrNot = $this->ask($askString);
                if ($addOrNot > 0 && $addOrNot < count($column) + 1 && strlen($addOrNot) == 1) {
                    $key = array_keys($column)[$addOrNot];
                    $selectedColumns[$key] = $column[$key];
                    break;
                }
                $this->info("{$addOrNot}不是一个有效的选项");
            }
        }
        return $selectedColumns;
    }

    private function conclusion($selectedColumns)
    {
        $this->info("所选字段如下 ： ");
        $i = 1;
        foreach ($selectedColumns as $column) {
            $this->info("$i. {$column}");
            $i++;
        }
    }
}
