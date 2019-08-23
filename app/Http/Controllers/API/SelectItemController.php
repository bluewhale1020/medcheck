<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SelectItem;
use App\SelectItemInfo;

class SelectItemController extends Controller
{


    public function getColumns(){
        $columns = SelectItemInfo::getTableColumns();

        return $columns;
    }
}
