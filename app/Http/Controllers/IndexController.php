<?php

namespace App\Http\Controllers;

use App\Exports\ScheduleExport;
use App\Utilities\ScheduleGenerator;
use App\Utilities\ScheduleReader;
use App\Utilities\TimeStampOrdering\StrictTO;
use App\Utilities\TimeStampOrdering\TimeStampManager;
use App\Utilities\TwoPhaseLocking\Basic2PL;
use App\Utilities\TwoPhaseLocking\Conservative2PL;
use App\Utilities\TwoPhaseLocking\LockManager;
use App\Utilities\TwoPhaseLocking\Strict2PL;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class IndexController extends Controller
{
    //
    public function index()
    {
        //TODO: Create Downloadable Excel File
        //TODO: Show Aborted Transactions
        //TODO: Fix Basic TO
        //TODO: Fix Basic 2PL
        //TODO: Add Ajax Request

//        $Sc = new ScheduleGenerator(1000,13);
//        $result = $Sc->generate();
//        $result = implode("\n",$result);
//        Storage::put('Schedule.txt', $result);
//        $lk = new LockManager();
//        $lk->sharedLock("","");

        set_time_limit(3600);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '3600');

        $sc = new ScheduleReader("Schedule.txt");
//        $ser = serialize($sc->readSchedules());
//        Storage::put('serial.txt', $ser);
//        --------------------------------------------Basic 2PL
//        $b2pl = new Basic2PL($sc->read());
//        $b2pl->run();
//        dd($b2pl->getTimes(),$b2pl->getTotalTime());
//        --------------------------------------------Conservative 2PL
//        $conservative = new Conservative2PL($sc->read());
//        $string = "";
//        foreach ($sc->read() as $schedule) {
//            foreach ($schedule as $item) {
//                $string .= $item->toString();
//            }
//            $string .= "\n";
//        }
//        dd($string);
//        $conservative->run();
//        dd($b2pl->getTimes(),$b2pl->getTotalTime(),$conservative->getTimes(),$conservative->getTotalTime());
//        --------------------------------------------Strict 2PL
//        $strict2PL = new Strict2PL($sc->read());
//        $strict2PL->run();
//        dd($strict2PL->getTimes(),$strict2PL->getTotalTime());
//        dd($strict2PL->getScheduleString(),$strict2PL->getExecutionString(),$strict2PL->getAbortedString());
//        --------------------------------------------Strict TO
        $strictTO = new StrictTO($sc->readSchedules());
        $strictTO->run();

        $schedules = $strictTO->getScheduleString();
        $executions = $strictTO->getExecutionString();
        $times = $strictTO->getTimes();
        $aborts = $strictTO->getAbortedString();
        $totalTime = $strictTO->getTotalTime();
        $algorithm = "Basic TO";

//        dd($strictTO->getTimes(),$strictTO->getAbortedString());
//        dd($strictTO->getScheduleString(),$strictTO->getExecutionString());

        return view("index",compact('totalTime','schedules','executions','times','aborts','algorithm'));
    }

    public function excel()
    {
        return Excel::download(new ScheduleExport, 'Schedule.xlsx');
    }
}
