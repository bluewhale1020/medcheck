<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'name_jp'
    ];



    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function exam_areas()
    {
        return $this->belongsToMany('App\ExamArea', 'exam_area_role', 'role_id', 'exam_area_id');
    }

    public function menus()
    {
        return $this->belongsToMany('App\Menu', 'menu_role','role_id','menu_id');
    }


    public static function getImagePath($role_id){
        $image_name = self::where('id',$role_id)->value('image_name');

        if(empty($image_name)){
            $image_name = 'user';
        }

        $path = 'img/roles/' . $image_name . '.png';

        return $path;

    }

    //[id=>日本語]リストの取得
    public static function getRoleList(){
        return self::get()->pluck('name_jp','id')->toArray();
    }

}
