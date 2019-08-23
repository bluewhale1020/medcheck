<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Configuration;
use App\ReceptionList;

use App\Events\saveModel;

use App\Http\Requests\API\ConfigurationRequest;

class ConfigurationController extends Controller
{
    public function index(){

        //健診簿リスト取得
        $reception_lists =ReceptionList::getReceptionLists();

        //設定データ取得
        $configurations = Configuration::getConfigData();


        return ['configurations'=>$configurations,'reception_lists'=>$reception_lists];
        
    }

    public function update(ConfigurationRequest $request){
        // return ['request'=> $request->all()];

        $result = Configuration::saveConfigData($request);

        // イベントをディスパッチする
        event(new saveModel(["設定の変更","update_configuration",5,"システムの設定を変更"]));

        return $result;
    }
}
