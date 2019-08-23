<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Statistic extends Model
{
    protected $fillable = [
        'name','value','name_jp'
    ];

    // 統計データをテーブルに保存
    public function saveStatisticsData($event_created_at,$data){

        $data['last_updated_time'] = $event_created_at;
        if(!empty($data['count_at_intervals'])){
            $data['count_at_intervals'] = implode(",",$data['count_at_intervals']) ;

        }

        \DB::beginTransaction();
        try {
                foreach ($data as $name => $value) {

                    $stat = self::where('name',$name)->first();
                    if(empty($stat)){
                        self::create([
                            'name'=>$name,
                            'value'=> $value,                        
                        ]);
                    }else{
                        $stat->value = $value;
            
                        $stat->save();
                    }
                }            

                \DB::commit();
                $result = ['result'=>true,'message'=>'統計データを保存しました。'];
                return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['result'=>false,'errors'=>[$e->getMessage()],'message'=>'統計データ保存中の異常エラー'];
           
        }

    }

    public function getStatisticsData($event_created_at){
        if(empty($last_updated = self::getLastUpdated())){
            return false;
        }  
        
        $new = Carbon::parse($event_created_at);
        $old = Carbon::parse($last_updated);

        if($new->gt($old)){
            return false;
        }else{
            $data = $this::get()->pluck('value','name')->toArray();
            $data['count_at_intervals'] = explode(",",$data['count_at_intervals']) ;
            return $data;
        }

    }

    public static function getLastUpdated(){
        return self::where('name','last_updated_time')->value('value');
    }
    
    public static function getInterval(){
        return self::where('name','interval')->value('value');
    }

    // 検査エリア削除時に関連項目を削除
    public static function deleteAreaCountRecords($area_name){

        $reserve_count = $area_name."対象者数";
        $complete_count = $area_name."完了数";

        $area = self::where('name', $reserve_count)->first();
        if($area){
            $area->delete();
        }

        $area = self::where('name', $complete_count)->first();
        if($area){
            $area->delete();
        }

    }
}
