<?php

namespace App\Http\Controllers;

use App\Exports\ScheduleExport;
use App\Utilities\ExcelHandler;
use App\Utilities\ScheduleReader;
use App\Utilities\TwoPhaseLocking\Conservative2PL;
use App\Utilities\TwoPhaseLocking\Strict2PL;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IndexController extends Controller
{
    private $handler = [];
    //
    public function index(Request $request)
    {
        //TODO: Fix Basic TO -> should be  redesign
        //TODO: Fix Basic 2PL -> should be design
        //TODO: Fix Conservative infinite loop -> has some bugs
        //TODO: make artisan command for execution times

        $handler = new ExcelHandler();

        if ($request->get('a') == "basic2pl"){
            $this->handler = $handler->basic2PL();
        }elseif($request->get('a') == "conservative2pl"){
            $this->handler = $handler->conservative2PL();
        }elseif($request->get('a') == "strict2pl"){
            $this->handler = $handler->strict2PL();
        }else{
            $this->handler = $handler->basicTO();
        }

        $schedules = $this->getScheduleString();
        $executions = $this->getExecutionString();
        $times = $this->getTimes();
        $aborts = $this->getAbortedString();
        $totalTime = $this->getTotalTime();
        $algorithm = $this->getAlgorithm();


        return view("index",compact('totalTime','schedules','executions','times','aborts','algorithm','request'));
    }

    public function excel(Request $request)
    {
        $handler = new ExcelHandler();
        if ($request->get('a') == "basic2pl"){
            $algorithm = "basic2PL";
        }elseif($request->get('a') == "conservative2pl"){
            $algorithm = "conservative2PL";
        }elseif($request->get('a') == "strict2pl"){
            $algorithm = "strict2PL";
        }else{
            $algorithm = "basicTO";
        }
        return Excel::download(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
    }

    private function getScheduleString()
    {
        return $this->handler[0];
    }

    private function getExecutionString()
    {
        return $this->handler[1];
    }

    private function getTimes()
    {
        return $this->handler[2];
    }

    private function getAbortedString()
    {
        return $this->handler[3];
    }

    private function getTotalTime()
    {
        return $this->handler[4];
    }

    private function getAlgorithm()
    {
        return $this->handler[5];
    }
}
