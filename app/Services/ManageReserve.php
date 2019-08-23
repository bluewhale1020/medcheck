<?php
/*
* class ManageReserve
* 予約リストページでの予約データの新規作成・編集用サービス
* accounts,reserve_infos,select_itemsテーブルに保存
* 既存のデータがあるかチェックし、あれば更新する
* 保存する前に、validationメソッドでデータ確認
*/

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 

use App\Account;
use App\ReserveInfo;
use App\SelectItem;
use App\ExamResult;
use App\Configuration;

use Carbon\Carbon;

class ManageReserve
{

    protected $accountError = [];
    protected $reserveError = [];
    protected $selectItemError = [];

    
    public function createReserve(Request $request){

        list($accountRequest,$reserveRequest,$selectRequest) = $this->sortRequestData($request);

        $result = $this->storeData($accountRequest,$reserveRequest,$selectRequest);

        if(!empty($result['errors'])){
            $this->failedValidations($result);
        }else{
            return $result;
        }        

    }
    
    public function updateReserve(Request $request,$reserve_info_id){

        list($accountRequest,$reserveRequest,$selectRequest) = $this->sortRequestData($request);

        $result = $this->updateData($accountRequest,$reserveRequest,$selectRequest,$reserve_info_id);

        if(!empty($result['errors'])){
            $this->failedValidations($result);
        }else{
            return $result;
        }        
    }

    public function failedValidations($result){
        // print_r($result['errors']);

        $response['status']  = 'NG';
        $response['message'] = $result['message'];
        $response['errors']  = $result['errors'];

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );        
    }    

    public function sortRequestData(Request $request){
        $accountRequest = null;
        $reserveRequest = null;
        $selectRequest = null;
        $selectData = [];

        //accounts
        $accountData['id_number'] = $request->id_number;
        $accountData['kana'] = $request->kana;
        $accountData['name'] = $request->name;
        $accountData['birthdate'] = $request->birthdate;
        $accountData['sex'] = $request->sex;

        $accountRequest = new Request($accountData);
        
        //reserve_info
        $reserveData['account_id'] = $request->account_id;
        if(empty($request->reception_list_id)){
            $reserveData['reception_list_id'] =  Configuration::getReceptionId();
        }else{
            $reserveData['reception_list_id'] = $request->reception_list_id;
        }

        $reserveData['serial_number'] = $request->serial_number;
        $reserveData['checkup_date'] = $request->checkup_date;
        $reserveData['schedule_date'] = $request->schedule_date;
        $reserveData['course'] = $request->course;
        $reserveData['kenpo'] = $request->kenpo;
        $reserveData['notes'] = $request->notes;
        $reserveData['check_in'] = 1;

        $reserveData = $this->setCheckupdate($reserveData);

        $reserveRequest = new Request($reserveData);

        //select_items
        $select_list = $request->select_list;
        $requestData = $request->all();

        foreach ($requestData as $key => $value) {
            if(\in_array($key,$select_list)){
                $selectData[$key] = $value;                
            }
        }

        $selectData['reserve_info_id'] = $request->reserve_info_id;          
        $selectRequest = new Request($selectData);


        return [$accountRequest,$reserveRequest,$selectRequest];
    }    


    public function setCheckupdate($reserveData){
        // print_r($reserveData);
        $now = Carbon::now();

        if (empty($reserveData['checkup_date']) or Carbon::createFromFormat('Y-m-d H:i:s', $reserveData['checkup_date']) === false) {
            $reserveData['checkup_date'] = $now->format('Y-m-d H:i:s');
        }
        if (empty($reserveData['schedule_date']) or Carbon::createFromFormat('Y-m-d H:i:s', $reserveData['schedule_date']) === false) {
            $reserveData['schedule_date'] = $reserveData['checkup_date'];
        }

        return $reserveData;
    }


    public function storeData($accountRequest,$reserveRequest,$selectRequest){

        $account = false;
        $reserve = false;
        $selectItem = false;

        \DB::beginTransaction();
        try {
            

            if($this->validateAccountData($accountRequest)){
                $account = Account::create($accountRequest->all());
            }


            if(empty($this->accountError) and !empty($account)){
                $reserveRequest->reception_list_id = Configuration::getReceptionId();                
                if($this->validateReserveInfoData($reserveRequest)){
                    $reserve = new ReserveInfo($reserveRequest->all());
                    $account->reserve_infos()->save($reserve);
                }                
            }
            if(empty($this->reserveError) and !empty($reserve)){
                if($this->validateSelectItemData($selectRequest)){
                    $selectItem = new SelectItem($selectRequest->all());
                    $reserve->select_item()->save($selectItem); 
                    
                    $reserve->exam_result()->create(); 
                }                
            }

            \DB::commit();
            $result = ['account'=>$account,'reserve'=>$reserve,'select_item'=>$selectItem,'errors'=>$this->getErrorInfo(),'message'=>$this->getUpdateMessage()];
            return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['exception'=>true,'errors'=>[$e->getMessage()],'message'=>'データ新規作成中の異常エラー'];
            // return false;            
        }        

    }


    public function updateData($accountRequest,$reserveRequest,$selectRequest,$reserve_info_id){

        $account = false;
        $reserve = false;
        $selectItem = false;
        $account_id = $reserveRequest->account_id;

        \DB::beginTransaction();
        try {
           

            if($this->validateAccountData($accountRequest)){
                $account = Account::find($account_id);
                $account->fill($accountRequest->all())->save();
            }


            if(empty($this->accountError) and !empty($account)){
                if($this->validateReserveInfoData($reserveRequest)){
                    $reserve = ReserveInfo::find($reserve_info_id);
                    $reserve->fill($reserveRequest->all())->save();                    
                }                
            }
            if(empty($this->reserveError) and !empty($reserve)){
                if($this->validateSelectItemData($selectRequest)){
                    $selectItem = SelectItem::where('reserve_info_id',$reserve_info_id)->first();
                    $selectItem->fill($selectRequest->all())->save();
                    
                    $examResult = ExamResult::where('reserve_info_id',$reserve_info_id)->first();
                    if(empty($examResult)){
                        $reserve->exam_result()->create();
                    }

                }                
            }

            \DB::commit();
            $result = ['account'=>$account,'reserve'=>$reserve,'select_item'=>$selectItem,'errors'=>$this->getErrorInfo(),'message'=>$this->getUpdateMessage()];
            return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['exception'=>true,'errors'=>[$e->getMessage()],'message'=>'データ更新中の異常エラー'];
            // return false;            
        }        

    }



    public function validateAccountData(Request $accountRequest){

        // バリデーションルール
        $validator = Validator::make($accountRequest->all(), [
            'kana' => 'required|string|max:60',
            'name' => 'sometimes|string|max:60',
            'id_number' => 'sometimes|alpha_dash|max:25',
            'birthdate' => 'required|date',
            'sex' => 'required|in:男,女'
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->accountError = $validator->errors();
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

    public function validateReserveInfoData(Request $reserveRequest){

        // バリデーションルール
        $validator = Validator::make($reserveRequest->all(), [
            // 'account_id' => 'required|integer',
            'checkup_date' => 'required|date',
            'reception_list_id' => 'required|integer',
            'course' => 'required|string',
            'kenpo' => 'required|in:0,1',
            'check_in' => 'required|in:0,1',
        ]);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->reserveError = $validator->errors();           
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  }            
            return false;
        }else{
            return true;
        }
 
    }     


    public function validateSelectItemData(Request $selectRequest){

        // バリデーションルール
        $rules = [
            // 'reserve_info_id' => 'sometimes|integer',
            'height'=>'in:0,1,2', 
            'weight'=>'in:0,1,2', 
            'bodyfat_ratio'=>'in:0,1,2',
            'abdominal_circumference'=>'in:0,1,2',
            'vision_test'=>'in:0,1,2',
            'hearing_test'=>'in:0,1,2',
            'hearing_test_conv'=>'in:0,1,2',
            'physical_examination'=>'in:0,1,2',
            'blood_pressure'=>'in:0,1,2',
            'urinary_test'=>'in:0,1,2',
            'urinary_test_type'=>'nullable|string',
            'urinary_sediment'=>'in:0,1,2',
            'blood_test'=>'in:0,1,2',
            'blood_test_type'=>'nullable|string',
            'fecaloccult_blood'=>'in:0,1,2',
            'electrogram_test'=>'in:0,1,2',
            'chest_xray'=>'in:0,1,2',              
        ];
    
    
        $validator = Validator::make($selectRequest->all(), $rules);

  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->selectItemError = $validator->errors();          
        //     foreach ($validator->errors()->all() as $error_text) {
        //         // $error_textにエラー内容が入る
        //         print($error_text);
        //  }              
            return false;
        }else{
            return true;
        }
 
    }

    protected function getUpdateMessage(){
        $message = '検査結果の入力に失敗：';
        if(!empty($this->accountError)){
            $message .= '受診者テーブルの更新に失敗しました。';
        }
        elseif(!empty($this->reserveError)){
            $message .= '予約テーブルの更新に失敗しました。';
        }
        elseif(!empty($this->selectItemError)){
            $message .= '検査項目テーブルの更新に失敗しました。';
        }
        else{
            $message = '予約情報を保存しました。';
        }

        return $message;
    }
    protected function getErrorInfo(){
        if(!empty($this->accountError)){
            return $this->accountError;
        }
        elseif(!empty($this->reserveError)){
            return $this->reserveError;
        }
        elseif(!empty($this->selectItemError)){
            return $this->selectItemError;
        }
        else{
            return '';
        }
  
        // return \array_merge($this->examResultError,$this->selectItemError); 

    }    

}