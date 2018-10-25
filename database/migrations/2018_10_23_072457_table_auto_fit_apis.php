<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableAutoFitApis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_fit_apis', function (Blueprint $table) {
            $table->increments('id');
            $table->string("uuid");
            $table->string("code");
            $table->string('api_name');
            $table->string('version');
            $table->mediumText("restrictions");
            $table->boolean("is_running");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('auto_fit_apis');
    }
}
