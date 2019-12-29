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

set_time_limit(3600);
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', '3600');

Route::get('/', 'IndexController@index')->name('index');
Route::get('/excel', 'IndexController@excel')->name('index.excel');
