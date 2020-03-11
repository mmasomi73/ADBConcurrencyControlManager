<?php

namespace App\Http\Controllers;

use App\Algorithm;
use App\Executed;
use App\Exports\ScheduleExport;
use App\Repositories\ScheduleRepository;
use App\Schedule;
use App\User;
use App\Utilities\ExcelHandler;
use App\Utilities\ScheduleReader;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class IndexController extends Controller
{
    private $handler = [];
    //
    public function index()
    {
        return view("index");
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
        Excel::store(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
//        return Excel::download(new ScheduleExport($handler,$algorithm), $algorithm.'.xlsx');
        return "Done...";
    }

    public function ajax(Request $request)
    {
        $handler = new ExcelHandler();

        if ($request->get('link') == "basic2pl"){
            $this->handler = $handler->basic2PL();
        }
        elseif($request->get('link') == "conservative2pl"){
            $this->handler = $handler->conservative2PL();
        }
        elseif($request->get('link') == "strict2pl"){
            $this->handler = $handler->strict2PL();
        }
        else{
            $this->handler = $handler->basicTO();
        }

        $schedules = $this->getScheduleString();
        $executions = $this->getExecutionString();
        $times = $this->getTimes();
        $aborts = $this->getAbortedString();
        $totalTime = $this->getTotalTime();
        $algorithm = $this->getAlgorithm();

        $data = new Collection();

        foreach($schedules as $key => $schedule) {
            $data->put($key,['time'=>$times[$key],
                        'schedule'=>$schedule,
                        'execution'=>$executions[$key],
                        'aborted'=>key_exists($key,$aborts)? implode(',',$aborts[$key]) : "-",
                ]);
        }

        return ['totalTime'=>$totalTime,'algorithm'=>$algorithm,'schedules'=>$data];
    }

    public function insertSchedules()
    {
        dd("This Functions is very Dangerous");
        $handler = new ExcelHandler();
        dd((new ScheduleRepository)->store($handler->getSchedules()));
        dd($handler->getSchedules());
    }

    public function panel()
    {
        $users = User::with('algorithms.algorithm')->orderByDesc('id')->paginate(14);
        $algorithms = Algorithm::count();
        $schedules = Schedule::count();
        $executions = Executed::count();
        return view('admin.index.index',compact('users','algorithms','schedules','executions'));
    }

    public function schedules()
    {

    }

//------------------------------------------= Private Methods
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
