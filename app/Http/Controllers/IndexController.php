<?php

namespace App\Http\Controllers;

use App\Utilities\ScheduleGenerator;
use App\Utilities\ScheduleReader;
use App\Utilities\TwoPhaseLocking\Basic2PL;
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
//        $lk = new LockManager();
//        $lk->sharedLock("","");

        $sc = new ScheduleReader("Schedule.txt");
//        --------------------------------------------Basic 2PL
//        $b2pl = new Basic2PL($sc->readSchedules());
//        $b2pl->run();
//        dd($b2pl->getTimes(),$b2pl->getTotalTime());

//        --------------------------------------------Basic 2PL
    }
}
