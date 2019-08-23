<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IoItemConversion extends Model
{
    public $timestamps = false;


    //健診簿のカラム名リストを返す
    public static function getTableColumns(){
        $columnNames = self::whereNotNull('list_order')->orderBy('list_order','asc')->get()->pluck('name');

        return $columnNames;
    }

 
}
