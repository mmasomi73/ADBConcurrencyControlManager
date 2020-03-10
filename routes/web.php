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
Route::get('/ajax', 'IndexController@ajax')->name('index.ajax');
Route::get('/ins', 'IndexController@insertSchedules')->name('index.insertSchedules');
Route::get('/panel', 'IndexController@panel')->name('index.panel');

Route::get('/users', 'UserController@index')->name('user.index');
Route::get('/user/{id}', 'UserController@view')->name('user.view');

Route::get('/schedules', 'ScheduleController@index')->name('schedule.index');
Route::get('/store', 'ScheduleController@readOutputs')->name('schedule.store');
Route::get('/schedule/{id}', 'ScheduleController@view')->name('schedule.view');

Route::get('/executions', 'ScheduleController@executions')->name('executions.index');
