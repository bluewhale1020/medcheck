<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReserveInfo extends Model
{

    protected $fillable = [
        'reception_list_id',
        'checkup_info_id',
        'account_id',     
        'serial_number',  
        'schedule_date',  
        'checkup_date',   
        'course',         
        'kenpo',          
        'notes',          
        'check_in',       
        'complete',
    ];


    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function reception_list()
    {
        return $this->belongsTo('App\ReceptionList');
    }

    public function exam_result()
    {
        return $this->hasOne('App\ExamResult');
    }

    public function select_item()
    {
        return $this->hasOne('App\SelectItem');
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($reserve) { // before delete() method call this
             $reserve->exam_result()->delete();
             $reserve->select_item()->delete();
        });
    }

    public function getReserveStat($reception_id,$interval){
        // 予約人数・受付数・完了数を取得する
        $reserve_count = ReserveInfo::where('reception_list_id',$reception_id)->count();
        $check_in_count = ReserveInfo::where('reception_list_id',$reception_id)->where('check_in',true)->count();
        $complete_count = ReserveInfo::where('reception_list_id',$reception_id)->where('complete',true)->count();
 
        // interval時間ごとに、受診者数を取得して、配列にする。
        $time_series = $this->getTimeSeriesData($reception_id,$interval);

        // 結果を返す
        return ['count_at_intervals'=>$time_series[1],'start_time'=>$time_series[0], 
        'reserve_count'=> $reserve_count,'check_in_count'=> $check_in_count,'complete_count'=> $complete_count];

    }

    //intervalごとの今日の受診者数の時系列データ
    public function getTimeSeriesData($reception_id,$interval = 30){

        $today = Carbon::today();
        $checkupdate = $today->format('Y-m-d');


        $unit = $interval * 60;
        $db_name = \DB::getDatabaseName();

        // $query = $this::select(\DB::raw("FROM_UNIXTIME(TRUNCATE(UNIX_TIMESTAMP(`checkup_date`) / ${unit}, 0) * ${unit}) AS datetime, COUNT(*) as count"))
        // ->whereDate('checkup_date', $checkupdate)->where('check_in',true)
        // ->groupBy(\DB::raw("TRUNCATE(UNIX_TIMESTAMP(`checkup_date`) / ${unit}, 0)"))->get();

        // Eloquentではエラーが出るので、生のSQLを使う
        $sql = "select FROM_UNIXTIME(TRUNCATE(UNIX_TIMESTAMP(`checkup_date`) / ${unit}, 0) * ${unit}) AS time, COUNT(*) as count 
        from ${db_name}.`reserve_infos` 
        where date(`checkup_date`) = \"${checkupdate}\" and `check_in` = 1 and `reception_list_id` = ${reception_id}
        group by TRUNCATE(UNIX_TIMESTAMP(`checkup_date`) / ${unit}, 0)";

        $result = \DB::select(\DB::raw($sql));

        $time_series = $this->createArrayFromCounts($today,$result,$interval);

        return $time_series;

    }


    // 受診者数データから、一日の$interval分ごとの人数を配列にする データが存在する範囲にしぼる
    public function createArrayFromCounts($today,$sql_result,$interval){

        $time_series = [];
        $interval_count = 0;
        $start_idx = 0;
        $total = count((array)$sql_result);

        $tommorrow = Carbon::today()->addDay(1); 
        $start = $end = false;
        while($today < $tommorrow){
            $found = false;
            foreach ($sql_result as $idx=>$record) {
                if($today->format('Y-m-d H:i:s') == $record->time){
                    $time_series[] = $record->count;
                    $found = true;
                    if(!$start){
                        $start = true;
                        $start_idx = $interval_count;
                    }
                    $total -= 1;

                    if($total == 0){
                        $end = true;
                    }

                    break;
                }
            }
            if($end){
                if($interval_count - $start_idx <4){
                    for ($i=0; $i < 4 -($interval_count - $start_idx); $i++) { 
                        $time_series[] = 0;                      
                    }
                }
                break;
            }
            if(!$found and $start and !$end){
                $time_series[] = 0;
            }


            $today->addMinutes($interval);
            $interval_count += 1;
        }

        return [$start_idx,$time_series];
    }


}
