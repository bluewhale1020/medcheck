<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultInfo extends Model
{
    public $timestamps = false;

    
    public function select_item_info()
    {
        return $this->belongsTo('App\SelectItemInfo');
    }

    // カテゴリ名から、対応する項目名リストを返す
    public static function getNamesFromCategory($category){
        if(empty($category)){
            return [];
        }

        return self::where('select_item_category',$category)->pluck('name')->toArray();

    }

    // カテゴリ名から、対応する項目名・日本語名を返す
    public static function getColumnsFromCategory($category){
        if(empty($category)){
            return [];
        }

        return self::where('select_item_category',$category)->pluck('name_jp','name')->toArray();

    }


    //結果データ順に結果名リストを返す
    public static function getTableColumns(){
        $columnNames = self::orderBy('exam_result_order','asc')->get()->pluck('name_jp');

        return $columnNames;
    }

    // 結果項目の小数点桁数をリストで返す(nullは除外)
    public static function getItemsDecimalPlaces(){

        return self::whereNotNull('num_decimal_places')->pluck('num_decimal_places','name');

    }


    // 対象項目IDから、対応する結果項目データを返す
    public static function getItemsFromSelectItemIDs($select_item_info_ids){
        if(empty($select_item_info_ids)){
            return [];
        }

        $resultInfo = self::whereIn('select_item_info_id',$select_item_info_ids)
        ->get();

        foreach ($resultInfo as $key => $itemInfo) {
            $sortedData[$itemInfo->select_item_category][$itemInfo->name] = $itemInfo;
        }

        return $sortedData;
    }


    //結果項目名リストからselect_item名と実施状況のリストを取得

    public static function getSelectItemsFromResultNames($resultData){
        if(empty($resultData)){
            return [];
        }

        $resultNames = array_keys($resultData);
        $resultInfo = self::whereIn('name',$resultNames)->get()->pluck('select_item_category','name');

        $selectItems = [];
        foreach($resultData as $key => $value){

            //視力は一部実施でＯＫ
            if($resultInfo[$key] == 'vision_test'){

                if(!empty($value) or $value === 0){
                    $selectItems[$resultInfo[$key]] = 2;
                }elseif(empty($selectItems[$resultInfo[$key]] )){
                    $selectItems[$resultInfo[$key]] = 1;
                }
                
            }else{
                //それ以外は全て実施が検査完了の条件
                if(empty($value) and $value !== 0){
                    $selectItems[$resultInfo[$key]] = 1;
                }elseif(empty($selectItems[$resultInfo[$key]])){
                    $selectItems[$resultInfo[$key]] = 2;
                }

            }

        }

        // $resultInfo = self::whereIn('name',$resultNames)
        // ->distinct()->select('select_item_category')->get()->pluck('select_item_category')->toArray();

        return $selectItems;

    }


}
