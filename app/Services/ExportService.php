<?php
/*
* class ExportService
* 健診簿を出力する為に、予約データをIoItemConversionテーブルにより整理し返す
*/

namespace App\Services;


use App\IoItemConversion;
use Carbon\Carbon;


class ExportService
{

    public function prepareReceptionList($reserveInfos){
        return  $this->sortData($reserveInfos);
    }


    //健診簿のデータに整形する
    public function sortData($reserveInfos){
        $sortedData = [];
        $io_table = IoItemConversion::whereNotNull('list_order')->orderBy('list_order','asc')->get();
        
        foreach ($reserveInfos as $key => $reserve) {
            $sortedData[$key] = [];

            foreach ($io_table as $idx => $io_item) {
                switch ($io_item->tb_name) {
                    case 'accounts':
                        $sortedData[$key][$idx] = $reserve->account[$io_item->item_name];
                        
                        break;
                    case 'reserve_infos':
                        if(empty($io_item->output_conversion)){
                            if($io_item->item_name == 'kenpo'){
                                $sortedData[$key][$idx] = $this->conv_selectitem_into_dot($reserve[$io_item->item_name]);
                            }else{

                                $sortedData[$key][$idx] = $reserve[$io_item->item_name];
                            }
                            
                        }else{
                            $func_name = $io_item->output_conversion;

                            $retData = $this->$func_name($io_item->item_name,$reserve[$io_item->item_name]);
  
                            if($retData){
                                $sortedData[$key][$idx] = $retData;

                            }else{
                                $sortedData[$key][$idx] = "";
                            }
                            
                        }               
                                                
                        break;
                    case 'select_items':   
                        
                        if(empty($io_item->output_conversion)){
                            $sortedData[$key][$idx] = $this->conv_selectitem_into_dot($reserve->select_item[$io_item->item_name]);

                        }else{
                            $func_name = $io_item->output_conversion;
                            $item_names = \explode(",",$io_item->item_name);
                            $retData = $this->conv_output_data($reserve->select_item,$item_names,$func_name);

                            if($retData){
                                $sortedData[$key][$idx] = $retData;

                            }else{
                                $sortedData[$key][$idx] = "";
                            }
                            
                        }
                        break;
                    
                    default:
                        $sortedData[$key][$idx] = "";
                        break;
                }
    
            }//foreach io_table
            
        }//foreach resrve_infos

        return $sortedData;

    }

    public function format_date($columnName,$str_datetime){
        // print_r($str_datetime);
        $dt = Carbon::parse($str_datetime);

        return $dt->toDateString();

    }
    
    public function format_time($columnName,$str_datetime){
        $dt = Carbon::parse($str_datetime);

        return $dt->toTimeString();

    }


    public function conv_selectitem_into_dot($itemData){

        if(empty($itemData)){
            return '';
        }else{
            return '●';
        }
    }

    public function conv_output_data($select_items,$item_names,$func_name){
        $retData = '';
        foreach ($item_names as $key => $item_name) {

            $retData = $this->$func_name($item_name,$select_items[$item_name]);

            if($retData != false){
                break;
            }
            
        }
        return $retData;
    }

    // カラムのデータを聴力データとして返す	
    public function func_output_hearing($columnName,$itemValue){

        // オージオ：hearing_test
        if($columnName == 'hearing_test'){
            if(empty($itemValue)){
                return false;
            }else{
                return "オージオ";
            }
             // 会話法 ：hearing_test_conv
        }elseif($columnName == 'hearing_test_conv'){
            if(empty($itemValue)){
                return false;
            }else{
                return "会話法";
            }            

        }
    }
    
	
    //blood_test_type又はurinary_test_typeカラムのデータを各カラムデータとして返す
    public function func_output_test_type($columnName,$itemValue){

        // 血液検査   blood_test_type 尿検査 urinary_test_type
        if($columnName == 'blood_test_type'){
            return $itemValue;
        }elseif($columnName == 'urinary_test_type'){
            $itemValue = \str_replace('+','・',$itemValue);
            return $itemValue;
        }else{
            return false;
        }        

    }
    



}