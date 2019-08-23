<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReserveInfo;
use App\ResultInfo;
use App\SelectItem;
use App\ExamResult;
use App\SelectItemInfo;
use App\IoItemConversion;
use App\ReceptionList;
use Excel;
use App\Exports\ViewExport;

use App\Services\ExportService;

class PrinterController extends Controller
{
    public function exportReceptionList(ExportService $export,Request $request){

        $reception_id = resolve('reception_id');

        $key = $request->search_key;
        $status = $request->status;
        $first_no = $request->first_no;
        $last_no = $request->last_no;
        
        // 予約リストの取得
        
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
            $query->where('check_in',$status);
        }
        if(!empty($first_no)){
            $query->where('serial_number','>=',$first_no);
        }
        if(!empty($last_no)){
            $query->where('serial_number','<=',$last_no);
        }
        // dd(\DB::getQueryLog());        
        $reserveInfos = $query->get();

        $sortedData =  $export->prepareReceptionList($reserveInfos);

        $columns = $this->getColumns('IoItemConversion');

        $filename = ReceptionList::getFileName($reception_id);

        $view = view('printer/exportReceptionList')->with(['sortedData'=> $sortedData,'columns'=>$columns]);

        return Excel::download(new ViewExport($view,'reception_list'),$filename);         

    }


    public function exportExamResult(Request $request){

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
        $reserveInfos = $query->get();
        $reserveInfos = ExamResult::convResultData($reserveInfos);
        // dd($reserveInfos);

        $columns = $this->getColumns('ResultInfo');
        //check_only以外の結果項目データ
        $decimal_places = ResultInfo::getItemsDecimalPlaces();

        $view = view('printer/exportExamResult')->with(['reserveInfos'=> $reserveInfos,'columns'=>$columns,'decimal_places'=>$decimal_places]);

        return Excel::download(new ViewExport($view,'result'),'result.xlsx');        
    }

    public function exportProgressChecklist(Request $request){

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
        $reserveInfos = $query->get();        
        
        
        // 各情報について、進捗度の計算
        foreach ($reserveInfos as $key => $oneReserve) {
            $reserveInfos[$key]['progress'] = $selctItem->calcProgress($oneReserve->select_item);
            unset($reserveInfos[$key]->select_item['id']);
            unset($reserveInfos[$key]->select_item['reserve_info_id']);
            unset($reserveInfos[$key]->select_item['created_at']);
            unset($reserveInfos[$key]->select_item['updated_at']);
        }

        $columns = $this->getColumns('SelectItemInfo');

        $view = view('printer/exportProgressChecklist')->with(['reserveInfos'=> $reserveInfos,'columns'=>$columns]);
        // return $view;   
        return Excel::download(new ViewExport($view,'progresslist'),'progresslist.xlsx'); 
    }


    // 項目の日本語名を取得
    public function getColumns($modelName){
        switch ($modelName) {
            case 'ResultInfo':
                $columns = ResultInfo::getTableColumns();
                break;
            case 'SelectItemInfo':
                $columns = SelectItemInfo::getTableColumns();
                break;
            case 'IoItemConversion':
                $columns = IoItemConversion::getTableColumns();
                break;
            default:
                $columns = false;
                break;
        }
        

        return $columns;
    }    
}
