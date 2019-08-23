<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\ExamArea;
use App\User;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $role = Role::with(['menus','exam_areas'])->where('id',Auth::user()->role_id)->first();
        $menus = $role->menus()->select('name')->get();
        
        $image_path = Role::getImagePath(Auth::user()->role_id);

        $area_list = $role->exam_areas()->select(['id','name'])->get();

        $online_users = User::getOnlineUsers();
        // \Debugbar::info($menus);
        return view('home')->with(['menus'=>$menus,'area_list'=>$area_list,'image_path'=>$image_path,'online_users'=>$online_users]);
    }

}
