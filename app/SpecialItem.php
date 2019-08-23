<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialItem extends Model
{
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


    //特殊項目名リストを返す
    public static function getNames(){
        return self::get()->pluck('name')->toArray();

    }

    //特殊項目名から、関連する検査項目をリストで返す
    public static function getRelatedItems($name){
        $specialItem = self::where('name',$name)->first()->toArray();

        $related_items = [];

        foreach ($specialItem as $item_name => $value) {
            if(\in_array($item_name,['id','name'])){
                continue;
            }
            if($value == 1){
                $related_items[] = $item_name;
            }
        }

        return $related_items;


    }

}
