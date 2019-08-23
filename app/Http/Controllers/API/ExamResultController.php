<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReserveInfo;
use App\ExamResult;
use App\ResultInfo;

class ExamResultController extends Controller
{
    public function index(Request $request,$load_mode = 'init'){
        
        $reception_id = resolve('reception_id');
       
        $key = $request->search_key;
        $checkupdate = $request->checkupdate;
        $status = $request->status;
        $first_no = $request->first_no;
        $last_no = $request->last_no;


        // \DB::enableQueryLog();
        // 受診中の個人・予約結果情報を取得
        $query = ReserveInfo::with(['exam_result','account']);

        $query->where('reception_list_id',$reception_id);

        if(!empty($key)){
            $query->whereHas('account', function($subquery) use ($key) {
                $subquery->where('kana','like',"%".$key."%")->orWhere('birthdate','like',"%".$key."%")
                ->orWhere('id_number','like',"%".$key."%");
            });
        }
        if(is_numeric($status)){
            $query->where('complete',$status);
        }
        if(!empty($checkupdate)){
            $query->whereDate('checkup_date',$checkupdate);
        }
        if(!empty($first_no)){
            $query->where('serial_number','>=',$first_no);
        }
        if(!empty($last_no)){
            $query->where('serial_number','<=',$last_no);
        }
        // dd(\DB::getQueryLog());
        $reserveInfos = $query->latest()->paginate(5); 

        if($load_mode == 'init'){

            //check_only以外の結果項目データ
            $decimal_places = ResultInfo::getItemsDecimalPlaces();

            // 各情報について一覧データを返す
            return ['reserveInfos'=>$reserveInfos, 'decimal_places'=>$decimal_places];        
        }else{
            return ['reserveInfos'=>$reserveInfos];        

        }
        
    }
    
    // 項目の日本語名を取得
    public function getColumns(){
        $columns = ResultInfo::getTableColumns();

        return $columns;
    }


}
