<?php

namespace App;
use App\ResultInfo;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{

    protected $fillable = [
        'reserve_info_id',
        'findings_chestabdomen',
        'height',
        'weight',
        'bodyfat_ratio',
        'abdominal_circumference',
        'r_eyesight',
        'l_eyesight',
        'corrected_r_eyesight',
        'corrected_l_eyesight',
        'r_hearing_1000hz',
        'l_hearing_1000hz',   
        'r_hearing_4000hz',
        'l_hearing_4000hz',
        'hearing_on_conv',
        'h_blood_pressure',
        'l_blood_pressure',
        'urinary_protein',
        'urinary_sugar',
        'urinary_urobilinogen',
        'urinary_ph',
        'urinary_blood',
        'eye_pressure_r',
        'eye_pressure_l',
        'lung_capacity',
        'lung_fev1_sec',
        'lung_fev1_per',
        'grip_strength_r',
        'grip_strength_l',
        'is_hungry',
        'hours_after_meals',
        'chest_xray_no',
        'stomach_xray_no',
        'electro_no',
        'eyeground_no'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


    public function reserve_info()
    {
        return $this->belongsTo('App\ReserveInfo');
    }


    //結果出力時に、視力の0.0を0.1未満に変更、聴力検査方法の追加
    public static function convResultData($reserveInfos){

        $vision_test_items = ResultInfo::getNamesFromCategory('vision_test');
        
        foreach ($reserveInfos as $key => $reserve) {
            // 視力が0.0の時のデータ修正　（0.1未満）
            foreach ($vision_test_items as $key => $item_name) {
                if(is_numeric($reserve->exam_result[$item_name]) and  $reserve->exam_result[$item_name] < 0.1){
                    $reserve->exam_result[$item_name] = '0.1未満';
                }
            }

            //聴力会話法か、オージオメータかで、聴力検査方法を指定する
            if($reserve->select_item->hearing_test > 0){
                $reserve->select_item->audiometry_method = 'オージオメータ';
            }else if($reserve->select_item->hearing_test_conv > 0){                
                $reserve->select_item->audiometry_method = '会話法';
            }


        }

        return $reserveInfos;
    }

}
