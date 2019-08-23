<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReserveInfo;
use App\SelectItem;
use App\SelectItemInfo;
use App\ExamArea;

class ProgressManagementController extends Controller
{
    public function index(Request $request){

        $reception_id = resolve('reception_id');

        $key = $request->search_key;
        $status = $request->status;
        $first_no = $request->first_no;
        $last_no = $request->last_no;

        $selctItem = new SelectItem();

        // \DB::enableQueryLog();
        // 受診中の予約情報を取得
        $query = ReserveInfo::with(['select_item','account']);

        $query->where('reception_list_id',$reception_id);
        
        if(!empty($key)){
            $query->whereHas('account', function($subquery) use ($key) {
                $subquery->where('kana','like',"%".$key."%")->orWhere('birthdate','like',"%".$key."%");
            });
        }
        if(is_numeric($status)){
            $query->where('complete',$status);
        }
        if(!empty($first_no)){
            $query->where('serial_number','>=',$first_no);
        }
        if(!empty($last_no)){
            $query->where('serial_number','<=',$last_no);
        }
        // dd(\DB::getQueryLog());
        $reserveInfos = $query->latest()->paginate(5);        
        
        
        // 各情報について、進捗度の計算
        foreach ($reserveInfos as $key => $oneReserve) {
            $reserveInfos[$key]['progress'] = $selctItem->calcProgress($oneReserve->select_item);
            unset($reserveInfos[$key]->select_item['id']);
            unset($reserveInfos[$key]->select_item['reserve_info_id']);
            unset($reserveInfos[$key]->select_item['created_at']);
            unset($reserveInfos[$key]->select_item['updated_at']);
        }

        // 一覧データを返す
        return $reserveInfos;
    }

    public function getColumns(){
        $columns = ExamArea::getColumnNames();

        return SelectItemInfo::getNameJpList($columns);
    }

}
