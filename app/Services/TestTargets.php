<?php
/*
* class TestTargets
* 検査エリアの対象者リストの取得
*
*/

namespace App\Services;

use Illuminate\Http\Request;

use App\ExamArea;
use App\ReserveInfo;
use App\SelectItem;
use App\SelectItemInfo;

class TestTargets
{
    protected $metabolites = [];

    public function getTestTargetInfos($exam_area_id,$reception_id,$request = null){
        //検査エリアの対象検査項目を取得
        // getTargetAreaItems
        $area_items = ExamArea::getTargetAreaItems($exam_area_id);
        // getTestTargetIds
        $target_ids = SelectItem::getTestTargetIds($area_items);
        if(empty($target_ids)){
            return [null,null,null,null];
        }
        // print("target_ids:".implode(",",$target_ids)."\n");

        // 受診中で、実施項目対象の個人・予約・検査項目情報を取得
        $query = $this->buildQuery($request,$target_ids,$area_items,$reception_id);
        $count = $query->count();
        $reserveInfos = $query->latest()->paginate(5);
        if(empty($reserveInfos)){
            return [null,null,null,null,null];
        }else{
            if($this->checkExamResultExists($reserveInfos) == false){
                $reserveInfos = $query->latest()->paginate(5);
            }
        }
        
        // 状況と項目データを取得して、レコードに追加
        $reserveInfos = $this->checkStatus($area_items,$reserveInfos);
        //check_only以外の検査項目ID
        $select_info_ids = SelectItemInfo::getIds($area_items,true);
        

        // list($reserveInfos,$area_items,$select_info_ids)
        return [$reserveInfos,$area_items,$select_info_ids,$count,$this->metabolites];
    }


    public function checkExamResultExists($reserveInfos){
        $result = true;

        foreach ($reserveInfos as $idx => $reserve) {
            if(empty($reserve->exam_result)){
                $reserve->exam_result()->create();
                $result = false;

            }
        }

        return $result;

    }

    public function getSelectColumns($area_items){

  
        $selectColumns = ['id','reserve_info_id'];

        foreach ($area_items as $key => $itemName) {
            $selectColumns[] =$itemName;
        }

        if(\in_array('urinary_metabolites',$selectColumns)){
            $this->metabolites = SelectItemInfo::getMetabolitesGroup();
            $selectColumns = \array_merge($selectColumns,array_keys($this->metabolites));
        }
        
        return $selectColumns;
    }

    public function getStatusQuery($query,$status,$area_items){
        //  \DB::enableQueryLog();
        if($status == 0){//実施途中
            $query->whereHas('select_item', function($subquery) use ($area_items) {
                foreach ($area_items as $key => $item) {
                    $subquery->where($item,'<',2);
                }
            });            
        }elseif($status == 1){//実施済み
            $query->whereHas('select_item', function($subquery) use ($area_items) {
                foreach ($area_items as $key => $item) {
                    $subquery->where($item,'!=',1);
                }
            });
        }
        // dd(\DB::getQueryLog());
        return $query;
    }

    //対象IDsとリクエストからクエリ作成
    public function buildQuery($request,$target_ids,$area_items,$reception_id){
      
        $query = ReserveInfo::with(['account','exam_result'])
        ->with(['select_item' => function ($query) use ($area_items) {
            $query->select($this->getSelectColumns($area_items));
        }])->where('check_in',true)->whereIn('id',$target_ids);
        // return $query;

        $query->where('reception_list_id',$reception_id);

        if(!empty($request)){
            $key = $request->search_key;
            $serial_number = $request->serial_number;
            $status = $request->status;
            $first_no = $request->first_no;
            $last_no = $request->last_no;

            if(!empty($key)){
                // $query->where(function ($query) use ($key) {
                //     $query->whereHas('account', function($subquery) use ($key) {
                //         $subquery->where('kana','like',"%".$key."%")->orWhere('birthdate','like',"%".$key."%")
                //         ->orWhere('id_number','like',"%".$key."%");
                //     })->orWhere('serial_number','=',$key);
                // });
                $query->whereHas('account', function($subquery) use ($key) {
                    $subquery->where('kana','like',"%".$key."%")->orWhere('birthdate','like',"%".$key."%")
                    ->orWhere('id_number','like',"%".$key."%");
                });
            }
            if(is_numeric($serial_number)){
                $query->where('serial_number','=',$serial_number);
            }
            if(is_numeric($status)){
                $query = $this->getStatusQuery($query,$status,$area_items);
                // $query->where('complete',$status);
            }
            if(!empty($first_no)){
                $query->where('serial_number','>=',$first_no);
            }
            if(!empty($last_no)){
                $query->where('serial_number','<=',$last_no);
            }
        }

              

        return $query;
    }

    // 実施・未実施のチェック（対象項目,検査項目）
    public function checkStatus($area_items,$reserveInfos){


        if(empty($area_items)){
            return false;
        }

        $item_taken = 0;

        // 検査項目と数値から、実施済み・一部実施・未実施を判定し、対象項目データと状況を返す
        foreach ($reserveInfos as $key => $oneReserve) {
            $item_taken = 0;
            $required = 0;

            
            foreach ($area_items as $idx => $itemName) {
                if(!empty($oneReserve->select_item[$itemName])){
                    $required += 1;
                    if( $oneReserve->select_item[$itemName] == 2){
                        $item_taken += 1;
                    }
                    // print("${itemName}:".$oneReserve->select_item[$itemName]."\n");
                }
            }
            // print("item_taken:${item_taken} item_no:${item_no}\n");
            $progress = $item_taken/$required;
            if($progress == 1){
                $status = "実施済み";
            }elseif($progress > 0){
                $status = "一部実施";
                
            }else{
                $status = "未実施";

            }

            $reserveInfos[$key]['progress'] = $status;
        }

        return $reserveInfos;
    }
    

}