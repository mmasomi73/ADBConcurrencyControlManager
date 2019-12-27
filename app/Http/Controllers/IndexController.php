<?php

namespace App\Http\Controllers;

use App\Utilities\ScheduleGenerator;
use App\Utilities\TwoPhaseLocking\LockManager;
use Illuminate\Http\Request;
use Storage;

class IndexController extends Controller
{
    //
    public function index()
    {
//        $Sc = new ScheduleGenerator(1000,13);
//        $result = $Sc->generate();
//        $result = implode("\n",$result);
//        Storage::put('Schedule.txt', $result);
        $lk = new LockManager();
        $lk->sharedLock("","");
    }
}
