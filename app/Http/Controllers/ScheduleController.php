<?php

namespace App\Http\Controllers;

use App\Repositories\ScheduleRepository;
use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index()
    {
        $schedules = Schedule::orderByDesc('id')->paginate(14);
        return view('admin.schedules.index',compact('schedules'));
    }

    public function view($id)
    {
        $schedule = (new ScheduleRepository)->getById($id);
        dd($schedule);
    }

    public function readOutputs()
    {

    }
}
