<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{

    protected $fillable = [
        'id',
        'name',
        'name_jp',
    ]; 

    public $timestamps = false;

    public function exam_areas()
    {
        return $this->hasMany('App\ExamArea');
    }

    //exam_categoriesの[id=>日本語]リストの取得
    public static function getCategoryList(){
        return self::get()->pluck('name_jp','id')->toArray();
    }
}
