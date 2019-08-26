<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    public static function getReceptionId(){
        return self::where('name','reception_list_id')->pluck('value')->first();
    }
    public static function setReceptionId($id){
        
        $config = self::where('name','reception_list_id')->first();
        $config->value = $id;
        $config->save();

    }    
    public static function getFirstSerialNo(){
        return self::where('name','first_serial_number')->pluck('value')->first();
    }
    public static function getReceptionListFilename(){
        return self::where('name','reception_list_filename')->pluck('value')->first();
    }


    //バーコードカラム名と白紙バーコードNOを返す
    public static function getBarcodeInfo(){
        $result = self::where('categories','barcode')->pluck('value','name');
        // print_r($result);
        if(!$result){
            return false;
        }

        $data = [
            'columns'=>[$result['barcode_column_name'],$result['barcode_column_name2']],
            'default_no'=>$result['default_barcode_no']
        ];
        return $data;
    }

    //設定データを編集用に取得
    public static function getConfigData(){

        $result = self::get()->pluck('value','name')->toArray();

        return $result;
    }

    //設定データをテーブルに保存
    public static function saveConfigData($request){

        \DB::beginTransaction();
        try {
                foreach ($request->all() as $name => $value) {

                    $config = self::where('name',$name)->first();
                    $config->value = $value;
        
                    $config->save();
                }            

                \DB::commit();
                $result = ['result'=>true,'message'=>'Configデータを保存しました。'];
                return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['result'=>false,'errors'=>[$e->getMessage()],'message'=>'Configデータ保存中の異常エラー'];
           
        }


    }

}
