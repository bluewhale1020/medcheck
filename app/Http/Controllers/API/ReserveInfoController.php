<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReserveInfo;
use App\SelectItemInfo;
use App\ReceptionList;
use App\Configuration;

use App\Events\saveModel;

use App\Services\ManageReserve;
use App\Services\ManageSerialNumber;

class ReserveInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reception_id = resolve('reception_id');
        // $reception_id = Configuration::getReceptionId();

        // 予約リストの取得
        $key = $request->search_key;
        $status = $request->status;
        $first_no = $request->first_no;
        $last_no = $request->last_no;

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
        $reserveInfos = $query->latest()->paginate(5); 


        // 項目の日本語名を取得
        $select_item_list = SelectItemInfo::getSelectItemList();

        // 通番情報を取得
        $serialNumberInfo = ManageSerialNumber::getSerialNumbers($reception_id);
        
        // 予約情報について一覧データを返す      
        return ['reserveInfos'=>$reserveInfos,'serial_numbers'=>$serialNumberInfo,'select_item_list'=>$select_item_list];        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManageReserve $reserveService,ManageSerialNumber $snManager,Request $request)
    {
        // return $request->all();
        // 通番の確認
        $serial_number = $snManager->validateSerialNumber($request->serial_number);
        if($serial_number){
            $request->serial_number = $serial_number;
        }else{
            $request->serial_number = '';
        }

        // 新規レコードを登録する(1)(2)
        $result = $reserveService->createReserve($request);
        // 通番情報更新
        $result['next_number'] =  $snManager->registerSerialNumber($serial_number);

        
        // イベントをディスパッチする
        event(new saveModel(["新規予約","create_reserve",3,"{$result['account']->name}の予約データを登録"]));
        
        // 結果・次の通番を返す
        return $result;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $barcode_no
     * @return \Illuminate\Http\Response
     */
    public function show($barcode_no)
    {
        $reception_id = resolve('reception_id');
        // $reception_id = Configuration::getReceptionId();        
        $data = Configuration::getBarcodeInfo();


        // 通番情報を取得
        $serialNumberInfo = ManageSerialNumber::getSerialNumbers($reception_id);


        //白紙受診票の場合はfalse
        if($barcode_no == $data['default_no']){
            return ['reserveInfos'=>false,'serial_numbers'=>$serialNumberInfo];        

        }


        // 予約リストの取得

        // \DB::enableQueryLog();
        // 受診中の予約情報を取得
        $query = ReserveInfo::with(['select_item','account']);

        $query->where('reception_list_id',$reception_id);

        $query->where(function($query) use($data,$barcode_no){
            $query->whereHas('account', function($subquery) use ($data,$barcode_no) {
                $subquery->where($data['columns'][0],$barcode_no);
            });
            $query->orWhere($data['columns'][1],$barcode_no); 
        });


      

        // dd(\DB::getQueryLog());
        $reserveInfos = $query->latest()->paginate(5); 


        
        // 予約情報について一覧データを返す      
        return ['reserveInfos'=>$reserveInfos,'serial_numbers'=>$serialNumberInfo];        


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManageReserve $reserveService,ManageSerialNumber $snManager,Request $request,$id)
    {
        // return $request->all();
        // 通番の確認
        $serial_number = $snManager->validateSerialNumber($request->serial_number,$id);
        if($serial_number){
            $request->serial_number = $serial_number;
        }else{
            $request->serial_number = '';
        }        
        // 新規レコードを登録する(1)(2)
        $result = $reserveService->updateReserve($request,$id);

        // 通番情報更新
        $result['next_number'] =  $snManager->registerSerialNumber($serial_number);

        // イベントをディスパッチする
        event(new saveModel(["予約編集","update_reserve",3,"{$result['account']->name}の予約データを更新"]));        

        // 結果・次の通番を返す
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,ManageSerialNumber $snManager)
    {
        $reserve = ReserveInfo::with('account')->findOrFail($id);
        $serial_number = $reserve->serial_number;
        $name = $reserve->account->name;
        $reserve->delete();
        $result = ['message'=>'指定した予約レコードは削除されました'];
        // 通番情報更新
        $result['next_number'] =  $snManager->deleteSerialNumber($serial_number);        

        // イベントをディスパッチする
        event(new saveModel(["予約削除","delete_reserve",4,"{$name}の予約データを削除"]));        

        // 結果・次の通番を返す
        return $result;        
    }
}
