<?php

namespace App\Http\Controllers;

use App\Algorithm;
use App\Repositories\ExecutedRepository;
use App\Repositories\ScheduleRepository;
use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index()
    {
        $schedules = Schedule::with('executions.user','executions.algorithm')->orderByDesc('id')->paginate(14);
//        return $schedules->first()->executions->where('algorithm_id',1);
        $algorithms = Algorithm::all();
        return view('admin.schedules.index',compact('schedules','algorithms'));
    }

    public function view($id)
    {
        $schedule = (new ScheduleRepository)->getById($id);
        dd($schedule);
    }

    public function readOutputs()
    {
        dd('Warning');
//        $schedule = (new ExecutedRepository)->store('mim',1,'basic2PL');
//        $schedule = (new ExecutedRepository)->store('mim',1,'basicTO');
//        $schedule = (new ExecutedRepository)->store('mim',1,'conservative2PL');
//        $schedule = (new ExecutedRepository)->store('mim',1,'strict2PL');

//        $schedule = (new ExecutedRepository)->store('abbasi',2,'basic2PL');
//        $schedule = (new ExecutedRepository)->store('abbasi',2,'basicTO');
//        $schedule = (new ExecutedRepository)->store('abbasi',2,'conservative2PL');
//        $schedule = (new ExecutedRepository)->store('abbasi',2,'strict2PL');
//
//        $schedule = (new ExecutedRepository)->store('moradi',3,'basic2PL');
//        $schedule = (new ExecutedRepository)->store('moradi',3,'basicTO');
//        $schedule = (new ExecutedRepository)->store('moradi',3,'conservative2PL');

//        dd($schedule);
    }
}

