<?php


/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

use App\Exports\ScheduleExport;
use App\Utilities\ExcelHandler;
use Maatwebsite\Excel\Facades\Excel;

Artisan::command('excel:basic2PL', function () {
    $this->comment('+---------------------+');
    $this->comment('| Working On It ...   |');
    $this->comment('+---------------------+');
    $handler = new ExcelHandler();
    $algorithm = "basic2PL";
    Excel::store(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
    $this->info('| Successfully Created|');
    $this->info('+---------------------+');
})->describe('create Excels basic2PL');

Artisan::command('excel:conservative2PL', function () {
    $this->comment('+---------------------+');
    $this->comment('| Working On It ...   |');
    $this->comment('+---------------------+');
    $handler = new ExcelHandler();
    $algorithm = "conservative2PL";
    Excel::store(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
    $this->info('| Successfully Created|');
    $this->info('+---------------------+');
})->describe('create Excels conservative2PL');

Artisan::command('excel:strict2PL', function () {
    $this->comment('+---------------------+');
    $this->comment('| Working On It ...   |');
    $this->comment('+---------------------+');
    $handler = new ExcelHandler();
    $algorithm = "strict2PL";
    Excel::store(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
    $this->info('| Successfully Created|');
    $this->info('+---------------------+');
})->describe('create Excels strict2PL');

Artisan::command('excel:basicTO', function () {
    $this->comment('+---------------------+');
    $this->comment('| Working On It ...   |');
    $this->comment('+---------------------+');
    $handler = new ExcelHandler();
    $algorithm = "basicTO";
    Excel::store(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
    $this->info('| Successfully Created|');
    $this->info('+---------------------+');
})->describe('create Excels basicTO');
