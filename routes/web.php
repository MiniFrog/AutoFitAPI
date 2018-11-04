<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\Route;

Route::prefix("")->group(function () {
    Route::get('/', "Administration@console");

    Route::get('/description/{APIName}/{version?}', "Administration@description");

    Route::get('/create/{APIName}/{version?}', "Administration@create");

    Route::post('/create/{APIName}/{version?}', "Administration@reprocess");
});


