<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DocumentController extends Controller
{
    public function manual($page = 'basic'){
        
        return view('pages/man_'.$page);

    }
}
