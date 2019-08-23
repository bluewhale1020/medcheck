<?php
/*
* class ManageSerialNumber
* 通番管理用サービス
* 
* 通番の空き確認、通番データの取得、更新
*/

namespace App\Services;

use Illuminate\Http\Request;


use App\ReserveInfo;
use App\Configuration;
use App\ReceptionList;



class ManageSerialNumber
{
    protected $reception_id;

    public function __construct(){
        $this->reception_id = Configuration::getReceptionId();
    }

    public function getReceptionId(){
        return $this->reception_id;
    }

    // 通番を最後の通番として保存、最大値と比較して必要なら更新する
    public function registerSerialNumber($serial_number){
        if(empty($serial_number)){
            return false;
        }

        $reception_list = ReceptionList::find($this->reception_id);

        $reception_list->last_serial_number = $serial_number;
        if(empty($reception_list->first_serial_number)){

            $reception_list->first_serial_number = $serial_number;
        }
        if(empty($reception_list->max_serial_number) or $reception_list->max_serial_number < $serial_number){

            $reception_list->max_serial_number = $serial_number;
        }


        $reception_list->save();

        return $reception_list->max_serial_number + 1;

    }

    // 削除する通番と通番情報を比較して必要なら更新する
    public function deleteSerialNumber($serial_number){
        if(empty($serial_number)){
            return false;
        }

        $reception_list = ReceptionList::find($this->reception_id);

        if($reception_list->first_serial_number == $serial_number){
            $min_number = ReserveInfo::where('reception_list_id',$this->reception_id)->min('serial_number');

            $reception_list->first_serial_number = $min_number;
        }
        if($reception_list->last_serial_number == $serial_number){
            $latest_number = ReserveInfo::where('reception_list_id',$this->reception_id)->latest()->value('serial_number');
            $reception_list->last_serial_number = $latest_number;
        }
        if($reception_list->max_serial_number == $serial_number){
            $max_number = ReserveInfo::where('reception_list_id',$this->reception_id)->max('serial_number');
            $reception_list->max_serial_number = $max_number;
        }
        $reception_list->save();

        return $reception_list->max_serial_number + 1;

    }

    // 通番が使用済みかチェックし、使用済みなら空いている番号を返す
    public function validateSerialNumber($serial_number,$reserve_info_id = null){
        
        if(empty($reserve_info_id)){

            $count = ReserveInfo::where('reception_list_id',$this->reception_id)->where('serial_number',$serial_number)->count();
        }else{
            $count = ReserveInfo::where('reception_list_id',$this->reception_id)->where('id','!=',$reserve_info_id)->where('serial_number',$serial_number)->count();
        }
        

        if($count > 0){
            $max_number = self::getMaxSerialNumber($this->reception_id);
            if($max_number){

                return $max_number + 1;
            }else{
                return false;
            }
        }else{
            return $serial_number;
        }
    }


    //通番管理情報を返す
    public static function getSerialNumbers($reception_id){

        $serialNumberInfo = ReceptionList::where('id',$reception_id)->select('first_serial_number','last_serial_number','max_serial_number')->first();
        
        if(empty($serialNumberInfo->first_serial_number)){
            return ['first_serial_number'=>Configuration::getFirstSerialNo(),'last_serial_number'=>0,'max_serial_number'=>0];
        }

        return $serialNumberInfo->toArray();
    }    
    
    //健診簿の更新の際の通番情報の整合性を合わせる
    public static function adjustNumbers($reception_id,$request){

        $stored_numbers = ReceptionList::where('id',$reception_id)->select('first_serial_number','last_serial_number','max_serial_number')->first();
        
        if(empty($stored_numbers->first_serial_number)){
            return $request;
        }

        if($stored_numbers->first_serial_number < $request->first_serial_number){
            $request->first_serial_number = $stored_numbers->first_serial_number;
        }
        if($stored_numbers->max_serial_number > $request->max_serial_number){
            $request->max_serial_number = $stored_numbers->max_serial_number;
        }


        return $request;
    } 

    //通番最大値取得
    public static function getMaxSerialNumber($reception_id){

        return ReceptionList::where('id',$reception_id)->pluck('max_serial_number')->first();
              
    }
}
