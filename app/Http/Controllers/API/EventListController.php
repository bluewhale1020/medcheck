<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\EventList;

class EventListController extends Controller
{
    public function index(){

        return EventList::orderBy('created_at','desc')->limit(20)->get();
        
    }
}
