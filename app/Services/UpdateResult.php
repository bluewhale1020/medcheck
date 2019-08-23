<?php
/*
* class UpdateResult
* 検査エリアの結果フォームデータをexam_results,select_itemsテーブルに保存
*
*/

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\Exceptions\HttpResponseException;  

use App\ExamResult;
use App\SelectItem;
use App\ResultInfo;

class UpdateResult
{

    protected $examResultError = [];
    protected $selectItemError = [];

    public function saveResultForm(Request $request, $reserve_info_id){
        
        list($resultRequest,$selectRequest) = $this->sortRequestData($request,$reserve_info_id);
        if(empty($resultRequest) and empty($selectRequest)){
            return ['exception'=>true,'message'=>["保存するデータがありません！"]];
        }

        $result = $this->saveData($resultRequest,$selectRequest,$reserve_info_id);

        if(!empty($result['errors'])){
            $this->failedValidations($result);
        }else{
            return $result;
        }
    }

    // json data {message: "The given data was invalid.", errors: {name: ["必須事項です"]}}
    public function failedValidations($result){
        $response['status']  = 'NG';
        $response['message'] = $result['message'];
        $response['errors']  = $result['errors'];

        throw new HttpResponseException(
            response()->json( $response, 422 )
        );        
    }

    public function sortRequestData(Request $request,$reserve_info_id){
        $resultRequest = null;
        $selectRequest = null;$selectData = [];

        $result_list = $request->result_list;
        $select_list = $request->select_list;
        $requestData = $request->all();

        foreach ($requestData as $key => $value) {
            if(\in_array($key,$result_list)){
                $resultData[$key] = $value;
            }elseif(\in_array($key,$select_list)){
                $selectData[$key] = $value;                
            }
        }
        if(!empty($resultData)){
            $selectItems = $this->getSelectItemsFromResultData($resultData);
            // print_r($resultData);
            $selectData = \array_merge($selectData, $selectItems);
            $resultData['reserve_info_id'] = $reserve_info_id;            
            $resultRequest = new Request($resultData);

        }
        if(!empty($selectData)){
            $selectData['reserve_info_id'] = $reserve_info_id;          
            $selectRequest = new Request($selectData);

        }
        return [$resultRequest,$selectRequest];
    }

    public function getSelectItemsFromResultData($resultData){
        $selectItems = ResultInfo::getSelectItemsFromResultNames($resultData );

        return $selectItems;

    }

    public function saveData($resultRequest,$selectRequest,$reserve_info_id){

        $examResult = false;                       
        $selectItem = false;     
        
        \DB::beginTransaction();
        try {
            
            if(!empty($resultRequest)){
                if($this->validateResultData($resultRequest)){
                    $examResult = ExamResult::where('reserve_info_id',$reserve_info_id)->first();
                    $examResult->fill($resultRequest->all())->save();
                }

            }
            if(empty($this->examResultError) and !empty($selectRequest)){
                if($this->validateSelectItemData($selectRequest)){
                    $selectItem = SelectItem::where('reserve_info_id',$reserve_info_id)->first();
                    $selectItem->fill($selectRequest->all())->save();
                }                
            }

            \DB::commit();
            $result = ['exam_result'=>$examResult,'select_item'=>$selectItem,'errors'=>$this->getErrorInfo(),'message'=>$this->getUpdateMessage()];
            return $result;
        } catch (\Exception $e) {
            \DB::rollBack();
            //echo $e->getMessage();
            return ['exception'=>true,'errors'=>[$e->getMessage()],'message'=>'データ保存中の異常エラー'];
            // return false;            
        }


    }

    public function validateResultData(Request $resultRequest){

        // バリデーションルール
        $rules = [
            'reserve_info_id' => 'required|integer',
            'findings_chestabdomen'=>'nullable|string', 
            'height'=>'nullable|numeric|max:250', 
            'weight'=>'nullable|numeric|max:250', 
            'bodyfat_ratio'=>'nullable|numeric|max:100',
            'abdominal_circumference'=>'nullable|numeric|max:250',
            'r_eyesight'=>'nullable|numeric|max:20',
            'l_eyesight'=>'nullable|numeric|max:20',
            'corrected_r_eyesight'=>'nullable|numeric|max:20',
            'corrected_l_eyesight'=>'nullable|numeric|max:20',
            'r_hearing_1000hz'=>'nullable|in:所見なし,聴取不可,所見あり',
            'l_hearing_1000hz'=>'nullable|in:所見なし,聴取不可,所見あり',
            'r_hearing_4000hz'=>'nullable|in:所見なし,聴取不可,所見あり',
            'l_hearing_4000hz'=>'nullable|in:所見なし,聴取不可,所見あり',
            'hearing_on_conv'=>'nullable|in:所見なし,聴取不可,所見あり',
            'h_blood_pressure'=>'nullable|integer|max:300', 
            'l_blood_pressure'=>'nullable|integer|max:150',
            'chest_xray_no'=>'nullable|integer',     
            'electro_no'=>'nullable|integer',
        ];
    
  
        $validator = Validator::make($resultRequest->all(), $rules);
  
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $this->examResultError = $validator->errors();
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
    public function validateSelectItemData(Request $selectRequest){

        // バリデーションルール
        $rules = [
            'reserve_info_id' => 'required|integer',
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
            'urinary_test_type'=>'string',
            'urinary_sediment'=>'in:0,1,2',
            'blood_test'=>'in:0,1,2',
            'blood_test_type'=>'string',
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
        if(!empty($this->examResultError)){
            $message .= '検査結果テーブルの更新に失敗しました。';
        }
        elseif(!empty($this->selectItemError)){
            $message .= '検査項目テーブルの更新に失敗しました。';
        }
        else{
            $message = '検査結果を保存しました。';
        }

        return $message;
    }
    protected function getErrorInfo(){
        if(!empty($this->examResultError)){
            return $this->examResultError;
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