<?php
/*
* class ImportService
* 健診簿の予約データをaccounts,reserve_infos,select_itemsテーブル用に整理し、保存
* 既存のデータがあるかチェックし、あれば更新する
* 保存する前に、validationメソッドでデータ確認
*/

namespace App\Services;

use Illuminate\Http\Request;
use Validator;

use App\Account;
use App\ReserveInfo;
use App\SelectItem;
use App\IoItemConversion;

class ImportService
{

    protected $accountError;
    protected $reserveError;
    protected $selectItemError;

    protected $str_datetime;


    public function importReceptionList($data){
        $sortedData = $this->sortData($data);
        // print("sort data\n");
        // print_r($sortedData);
        // print("\n");
        return $this->saveData($sortedData);
    }

    public function sortData($rawData){
        $accounts = [];
        $reserve_infos = [];
        $select_items = [];
        foreach ($rawData as $colName => $itemData) {
            # code...
            $itemInfo = IoItemConversion::where('name',$colName)->select(['item_name','tb_name','input_conversion'])->first();

            if(empty($itemInfo)){
                continue;
            }

            if(empty($itemInfo->input_conversion)){
                ${$itemInfo->tb_name}[$itemInfo->item_name] = $itemData;
                
            }else{
                $func_name = $itemInfo->input_conversion;
                // if($func_name == 'func_select_hearing_type'){
                    $retData = $this->$func_name($colName,$itemData);
                    if($retData){
                        ${$itemInfo->tb_name} += $retData;
                    }
                // }elseif($func_name == 'func_check_test_type'){
                //     $retData = $this->func_check_test_type($colName,$itemData);
                //     ${$itemInfo->tb_name}[$itemInfo->item_name] = $itemData;
                // }
                
            }
        }
        //reception_list_id
        if(!empty( $rawData['reception_list_id'])){

            $reserve_infos['reception_list_id'] = $rawData['reception_list_id'];
        }
        //協会けんぽ
        if(!empty( $reserve_infos['kenpo']) and $reserve_infos['kenpo'] == '●'){

            $reserve_infos['kenpo'] = 1;
        }
        $select_items = $this->conv_selectitemdata_into_num($select_items);
        return ['accounts'=>$accounts,'reserve_infos'=>$reserve_infos,'select_items'=>$select_items];


    }

    public function conv_selectitemdata_into_num($select_items){
        foreach ($select_items as $itemName => $itemData) {
            if($itemData == '●'){
                $select_items[$itemName] = 1;
            }
        }

        return $select_items;
    }

    public function get_datetime($columnName,$itemValue){
        if($columnName == '受診予定日'){
            if(empty($itemValue)){
                $this->str_datetime = '';
                
            }else{

                $this->str_datetime = $itemValue;
            }
            return false;
        }elseif(!empty($this->str_datetime) and $columnName == '予約時間'){
            if(!empty($itemValue)){
                $this->str_datetime .= " " . $itemValue;
            }else{
                $this->str_datetime .= " " . '00:00:00';
                
            }
            return ['schedule_date'=>$this->str_datetime];
        }else{
            return false;
        }
    }

    public function func_select_hearing_type($columnName,$itemValue){

        // null: hearing_testに１
        if(empty($itemValue)){
            return ['hearing_test'=>1];


            // 聴力データにより、
            // オージオ：hearing_test  会話法 ：hearing_test_conv
            // カラムに１を登録
        }elseif($itemValue == 'オージオ'){
            return ['hearing_test'=>1];
        }elseif($itemValue == '会話法'){
            return ['hearing_test_conv'=>1];
        }
    }

    public function func_check_test_type($columnName,$itemValue){
        // blood_test／urinary_testに１
        // 入力データを
        // blood_test_type又は urinary_test_typeに登録
        if($columnName == '血液検査'){
            return ['blood_test'=>1,'blood_test_type'=>$itemValue];
        }elseif($columnName == '尿検査2'){
            $itemValue = \str_replace('・','+',$itemValue);
            return ['urinary_test_type'=>$itemValue];
        }else{
            return false;
        }
        
    }


    public function saveData($sortedData){

        $accounts = new Request($sortedData['accounts']);
        $reserve_infos = new Request($sortedData['reserve_infos']);
        $select_items = new Request($sortedData['select_items']);

        \DB::beginTransaction();
        try {

            $account = $this->saveAccount($accounts);
            if($account){
                $reserve = $this->saveReserveInfo($reserve_infos,$account);
                if($reserve){

                    $select_item = $this->saveSelectItem($select_items,$reserve);
                }else{
                    $select_item = false;
                }
            }else{
                $reserve = $select_item = false;
            }

            \DB::commit();
            $result = ['account'=>$account,'reserve'=>$reserve,'select_item'=>$select_item,'errors'=>$this->getErrorInfo()];
            return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['exception'=>true,'errors'=>[$e->getMessage()]];
            // return false;            
        }

    }

    protected function getErrorInfo(){
        
        return ['account'=>$this->accountError,'reserve'=>$this->reserveError,'select_item'=>$this->selectItemError];
    }

    public function saveAccount(Request $accounts){

        if(!$this->validateAccountData($accounts)){
            return false;
        }

        $account = Account::where('kana',$accounts['kana'])->where('birthdate',$accounts->input('birthdate'))->first();
        if(empty($account)){
            
            $account = Account::create($accounts->all());
        }else{
            
            $account->fill($accounts->all())->save();
        }

        return $account;
    }

    public function saveReserveInfo(Request $reserve_infos,$account){
        $reserve = ReserveInfo::where('account_id',$account->id)->where('schedule_date',$reserve_infos->input('schedule_date'))->first();

        if($reserve){

                if(!$this->validateUpdateReserveInfoData($reserve_infos)){
                    return false;
                }                 
                $reserve->fill($reserve_infos->all())->save();
               
 
        }else{
            if(!$this->validateCreateReserveInfoData($reserve_infos)){
                return false;
            }            
            $reserve = $account->reserve_infos()->create($reserve_infos->all());
 
        }
        return $reserve;
    }

    public function saveSelectItem(Request $select_items,$reserve){
        
        if(!$this->validateSelectItemData($select_items)){
            return false;
        }
        
        $select_item = SelectItem::where('reserve_info_id',$reserve->id)->first();
        if($select_item){
            $select_item->fill($select_items->all())->save(); 
           
        }else{
            $select_item = $reserve->select_item()->create($select_items->all());
            $reserve->exam_result()->create();
        }
        return $select_item;        
    }


    public function validateAccountData(Request $accounts){

        // バリデーションルール
        $validator = Validator::make($accounts->all(), [
            'kana' => 'required|string|max:60',
            'name' => 'sometimes|string|max:60',
            'id_number' => 'sometimes|alpha_dash|max:25',
            'birthdate' => 'required|date',
            'sex' => 'required|in:男,女'
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->accountError = $validator->errors()->all();
        //     print_r($accounts->all());             
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  } 
            return false;
        }else{
            return true;
        }
 
    }
    public function validateCreateReserveInfoData(Request $reserve_infos){

        // バリデーションルール
        $validator = Validator::make($reserve_infos->all(), [
            'reception_list_id' => 'required|integer',
            'checkup_info_id' => 'required|integer',
            // 'account_id' => 'required|integer',
            'schedule_date' => 'required|date',
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->reserveError = $validator->errors()->all();         
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  }              
            return false;
        }else{
            return true;
        }
 
    }
    public function validateUpdateReserveInfoData(Request $reserve_infos){

        // バリデーションルール
        $validator = Validator::make($reserve_infos->all(), [
            // 'account_id' => 'required|integer',
            'schedule_date' => 'required|string',
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->reserveError = $validator->errors()->all();           
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  }            
            return false;
        }else{
            return true;
        }
 
    }    
    public function validateSelectItemData(Request $select_items){

        // バリデーションルール
        $validator = Validator::make($select_items->all(), [
            // 'reserve_info_id' => 'required|integer',
            'urinary_test_type' => 'sometimes|string',
            'blood_test_type' => 'sometimes|string',            
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->selectItemError = $validator->errors()->all();          
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  }              
            return false;
        }else{
            return true;
        }
 
    }

}