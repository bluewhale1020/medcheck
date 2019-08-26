<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReceptionList;
use App\Configuration;

use App\Events\saveModel;

use App\Services\ImportService;
use App\Services\ManageSerialNumber;
use App\Http\Requests\API\CreateReceptionListRequest;

class ReceptionListController extends Controller
{

    public function create(CreateReceptionListRequest $request){
        
        $expiration_date = ReceptionList::calcExpirationDate($request->import_date);
        $receptionList = ReceptionList::where('name',$request->name)->first();

        if($receptionList){
            $receptionList->expiration_date = $expiration_date;
            $saveData = [
                'name'=> $request->name,
                'import_date'=> $request->import_date,
                'main_course'=> $request->main_course,
                'main_kenpo'=> $request->main_kenpo,
            ];
            if(!empty($request->first_serial_number)){
                $request = ManageSerialNumber::adjustNumbers($receptionList->id,$request);
                $saveData['first_serial_number'] = $request->first_serial_number;
                $saveData['last_serial_number'] = $request->last_serial_number;
                $saveData['max_serial_number'] = $request->max_serial_number;
            }

            $receptionList->fill($saveData)->save();
            // イベントをディスパッチする
            event(new saveModel(["健診簿リストの更新","update_reception_list",4,"ID{$receptionList->id}の有効期限を{$expiration_date}に更新"]));
        }else{
            $receptionList = new ReceptionList();
            $receptionList->expiration_date = $expiration_date;
            $receptionList->fill($request->all())->save();            
            // $receptionList = ReceptionList::create($request->all());
            // イベントをディスパッチする
            event(new saveModel(["健診簿レコード新規作成","create_reception_list",4,"ID{$receptionList->id}の健診簿データを登録"]));

        }
        //現在使用中の健診簿IDに設定
        Configuration::setReceptionId($receptionList->id);

        return ['list'=>$receptionList];

    }    

    public function import(ImportService $import,Request $request){

        $data = $request->data;

        $result = $import->importReceptionList($data);

        // イベントをディスパッチする
        event(new saveModel(["健診簿アップロード","upload_reception_list",4,"健診簿の健診予約データをシステムにアップロード"]));        
        //  $result = ['account'=>true,'reserve'=>true,'select_item'=>true,'postdata'=>$data];
        return ['result'=>$result];
    }   

        

}
