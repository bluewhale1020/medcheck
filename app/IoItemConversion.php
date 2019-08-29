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
    
    //健診簿の必須カラム名リストを返す
    public static function getRequiredColumns(){
        $columnNames = self::where('required',true)->pluck('name');

        return $columnNames;
    }
 
}
