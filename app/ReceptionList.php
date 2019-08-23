<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReceptionList extends Model
{

    protected $fillable = [
        'name', 
        'import_date',
        'expiration_date',
        'main_course',
        'main_kenpo',
        'first_serial_number',
        'last_serial_number',
        'max_serial_number',
    ];   
    

    public function reserve_infos()
    {
        return $this->hasMany('App\ReserveInfo');
    }

    public static function getFileName($reception_id){
        return self::where('id',$reception_id)->value('name');
    }

    public static function calcExpirationDate($import_date){
        $dt = Carbon::parse($import_date);
        return $dt->addMonth();
    }

    public static function getReceptionLists(){

        $result = self::get()->pluck("name","id")->toArray();

        if(empty($result)){
            return false;
        }

        return $result;
    }


}
