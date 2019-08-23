<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ExamArea extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "name",
        "exam_category_id",
        "height",
        "weight",
        "bodyfat_ratio",
        "abdominal_circumference",
        "vision_test",
        "hearing_test",
        "hearing_test_conv",
        "physical_examination",
        "blood_pressure",
        "urinary_test",
        "urinary_sediment",
        "blood_test",
        "fecaloccult_blood",
        "electrogram_test",
        "chest_xray",
        "stomach_xray",
        "eye_pressure",
        "eyeground",
        "grip_strength",
        "lung_capacities",
        "urinary_metabolites",        
    ];

    public function exam_category()
    {
        return $this->belongsTo('App\ExamCategory');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'exam_area_role', 'exam_area_id', 'role_id');
    }

    //exam_areasテーブルのカラム名を取得
    public static function getColumnNames(){
        $table_columns = DB::getSchemaBuilder()->getColumnListing('exam_areas');

        //検査項目以外を削除
        $result = array_diff($table_columns, ['id', 'name', 'exam_category_id','created_at','updated_at']);
        //indexを詰める
        return array_values($result);    
    }


    // 検査エリアの対象検査項目を取得して返す
    public static function getTargetAreaItems($exam_area_id){
        if(empty($exam_area_id)){
            return false;
        }

        $examArea = self::where('id',$exam_area_id)->first();
        $collection = collect($examArea->toArray())->except(['id','name','exam_category_id','created_at','updated_at']);
        $filtered = $collection->filter(function($value, $key){
            return !empty($value);
        });
       
        return $filtered->keys()->toArray();
    }

    // 全ての検査エリアの対象検査項目を取得して返す
    public static function getAllAreaItems(){

        $all_area_items = [];

        $exam_areas = self::all()->keyBy('name');
        foreach ($exam_areas as $name => $area_data) {
            $collection = collect($area_data->toArray())->except(['id','name','exam_category_id','created_at','updated_at']);
            $filtered = $collection->filter(function($value, $key){
                return !empty($value);
            });  
            $all_area_items[$name] =$filtered->keys()->toArray();
        }
       
        return $all_area_items;
    }

    // 検査エリア名リストを返す
    public static function getAreaNames(){
        return self::get()->pluck('name');
    }
}


