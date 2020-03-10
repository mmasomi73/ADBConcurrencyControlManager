<?php


namespace App\Http\Controllers;


use App\Algorithm;
use App\Repositories\ExecutedRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){
        $users = User::with('algorithms.algorithm')->orderByDesc('id')->paginate(14);
        return view('admin.students.index',compact('users'));
    }

    public function view($id, Request $request){

        $user = User::where('id',$id)->first();
        if (empty($user)) return redirect(route('user.index'));
        $algorithm = null;
        if ($request->get('algorithm') == 'basic2PL') $algorithm = 1;
        if ($request->get('algorithm') == 'basicTO') $algorithm = 4;
        if ($request->get('algorithm') == 'conservative2PL') $algorithm = 2;
        if ($request->get('algorithm') == 'strict2PL') $algorithm = 3;
        $executions = (new ExecutedRepository)->getAllByUser($user->id,$algorithm);
        return view('admin.students.view',compact('user','executions','algorithm'));
    }
}
