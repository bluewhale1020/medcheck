<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelectItemInfo extends Model
{
    public $timestamps = false;

    public function result_info()
    {
        return $this->hasMany('App\ResultInfo');
    }

    //検査項目データ順に検査名リストを返す
    public static function getTableColumns(){
        $columnNames = self::orderBy('select_item_order','asc')->get()->pluck('name_jp');

        return $columnNames;
    }


    //検査エリアテーブルカラム用の検査名リストを返す
    public static function getExamAreaColumns($area_items){
        if(empty($area_items)){
            return false;
        }
        $columnNames = self::whereIn('name',$area_items)->orderBy('select_item_order','asc')->get()->pluck('name_jp','name');
        return $columnNames;

    }    

    //英語項目名リストを英語・日本語項目名リストに変換
    public static function getNameJpList($names){
        
        if(empty($names)){
            return [];
        }

        return self::whereIn('name',$names)->pluck('name_jp','name')->toArray();

    }
    
    

    //項目IDから検査項目のnameを返す
    public static function getNames($ids){
        
        if(empty($ids)){
            return false;
        }

        $itemInfos = self::whereIn('id',$ids)->get();

        return $itemInfos->pluck('name')->toArray();
    }

    //検査項目のnameから項目IDを返す
    public static function getIds($names,$exclude_check_only = false){
        
        if(empty($names)){
            return false;
        }
        $query = self::query();
        if($exclude_check_only){
            $query->where('check_only',0);
        }

        $itemInfos = $query->whereIn('name',$names)->get();

        return $itemInfos->pluck('id')->toArray();
    }


    // 検査エリアの検査項目の英語・日本語リストを返す
    public static function getAreaItemList($area_items){
        if(empty($area_items)){
            return false;
        }
        $itemInfos = self::whereIn('name',$area_items)->where('check_only',1)->get()->pluck('name_jp','name');
        return $itemInfos->toArray();

    }


    // 予約リストの検査項目の英語・日本語リストを返す
    public static function getSelectItemList(){

        $advanced_list = self::where('grouping','!=','')->orderBy('select_item_order','asc')
        ->select('name','name_jp','options','grouping')->get()->groupBy('grouping');

        $basic_list = self::where('grouping','')->orderBy('select_item_order','asc')->get()->pluck('name_jp','name')->toArray();
        return ['basic'=>$basic_list,'advance'=>$advanced_list];

    }


    //尿代謝物のリストを返す
    public static function getMetabolitesGroup(){

        return self::where('check_only',false)->where('grouping','metabolites')->orderBy('select_item_order','asc')->get()->pluck('name_jp','name')->toArray();

    }    
}
