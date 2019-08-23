<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;

class TestController extends Controller
{
    public function import_service(ImportService $service,Request $request){



        echo $service->exampleFunc($request);

    }
}
