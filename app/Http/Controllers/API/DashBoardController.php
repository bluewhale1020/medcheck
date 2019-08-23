<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EventList;
use App\Statistic;
use App\ReserveInfo;
use App\ExamArea;
use App\SelectItem;

class DashBoardController extends Controller
{
    public function statIndex(Statistic $stat,ReserveInfo $reserve_info){

        // 最新イベント日時を取得
        $event_created_at = EventList::getMostRecentTime();


        // 検査エリア名リストを取得
        $area_names = ExamArea::getAreaNames();
        // 履歴が最新か
        $stat_data = $stat->getStatisticsData($event_created_at);
        // return $stat_data;
        if($stat_data == false){
            $stat_data = [];
            $reception_id = resolve('reception_id');
            
            //     予約統計
            $reserve_stat = $reserve_info->getReserveStat($reception_id,$stat->getInterval());
            $stat_data += $reserve_stat;

            //     エリア統計
            $all_area_items = ExamArea::getAllAreaItems();
            
            foreach ($all_area_items as $area_name => $area_items) {
                // [$area_name.'完了数'=>$complete,$area_name.'対象者数'=>$total]
                $area_counts = SelectItem::getTestTargetCounts($area_name, $area_items,$reception_id);
                $stat_data += $area_counts;
            }

            // 統計データの更新
            $save_result = $stat->saveStatisticsData($event_created_at,$stat_data);
            // 集計した統計データを返す
            return ['stat_data'=>$stat_data,'save_result'=>$save_result,'area_names'=>$area_names];
        }else{
            // 集計した統計データを返す
            return ['stat_data'=>$stat_data,'area_names'=>$area_names];

        }
        
    }
}
