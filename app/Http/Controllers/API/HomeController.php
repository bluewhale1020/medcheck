<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;

class HomeController extends Controller
{


    public function getAreaList($role_id){
        $role = Role::with(['menus','exam_areas'])->where('id',$role_id)->first();
        $area_list = $role->exam_areas()->select(['id','name'])->get();        

        return ['area_list'=>$area_list];
    }
}
