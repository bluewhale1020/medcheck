<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\TestTargets;
use App\Services\UpdateResult;
use App\SelectItemInfo;
use App\SelectItem;
use App\ReserveInfo;
use App\ResultInfo;
use App\ExamArea;
use App\ExamCategory;
use App\Role;

use App\Events\saveModel;

use App\Http\Requests\API\ExamAreaRequest;
use App\Services\ManageImage;

class ExamAreaController extends Controller
{
    public function getResultIndex(TestTargets $test_targets,$exam_area_id,$load_mode = 'init',Request $request){//
        // return [$exam_area_id,$request];
        $reception_id = resolve('reception_id');

        // 対象者リストの取得
        list($reserveInfos,$area_items,$select_info_ids,$count,$metabolites) = $test_targets->getTestTargetInfos($exam_area_id,$reception_id,$request);
        
        //area情報
        $area_info = [
            'name' =>ExamArea::where('id',$exam_area_id)->first()->name,
            'count'=>$count
        ];
        if($load_mode == 'init'){

            // check_only検査項目の英語・日本語リストを取得
            $item_list = SelectItemInfo::getAreaItemList($area_items);
            
            //check_only以外の結果項目データ
            $result_infos = ResultInfo::getItemsFromSelectItemIDs($select_info_ids);

            // 各情報についてデータを返す
            return ['reserveInfos'=>$reserveInfos, 'item_list'=>$item_list,'result_infos'=>$result_infos,'area'=>$area_info,'metabolites'=>$metabolites];
        }else{
            return ['reserveInfos'=>$reserveInfos,'area'=>$area_info];
        }

    }

    public function updateResult(UpdateResult $updateService,SelectItem $select,Request $request, $reserve_info_id){
        // return [$request->all(),$reserve_info_id];
        
        $result =  $updateService->saveResultForm($request, $reserve_info_id);

        $reserve = ReserveInfo::with('account','select_item')->find($reserve_info_id);
        
        //代謝物関連項目、電離放射線、鉛、粉じん、血清インジウム、ホルムアルデヒドについて、検査対象ならば検査済みかチェック
        $select->updateSpecialItems($reserve->select_item);

        //進捗度が１００なら、健診終了にする
        if(($progress = $select->calcProgress($reserve->select_item))  == 100){
            $reserve->complete = true;


            $reserve->save();
            // イベントをディスパッチする
            event(new saveModel(["個別健診終了","checkup_complete",3,"{$reserve->account->name}の健診が終了"]));

        }else{
            // イベントをディスパッチする
            event(new saveModel(["検査結果の更新","update_result",3,"{$reserve->account->name}の検査結果を更新(進捗度{$progress}%)"]));
        }   

        return $result;
    }


    public function getColumns(){
        $table_columns = ExamArea::getColumnNames();

        $columns = SelectItemInfo::getExamAreaColumns($table_columns);

        return $columns;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //エリアカテゴリリストの取得
        $exam_category_list = ExamCategory::getCategoryList();

        //役柄リストの取得
        $roles = Role::getRoleList();

        //検査エリアリストの取得
        $key = $request->search_key;
        $exam_category_id = $request->exam_category_id;


        $query = ExamArea::with(['exam_category','roles']);

        if(!empty($key)){
            $query->where('name','like',"%".$key."%");
        }
        if(!empty($exam_category_id)){
            $query->where('exam_category_id',$exam_category_id);
        }

        $exam_areas = $query->orderBy('id','asc')->paginate(5);

        return ['category_list'=>$exam_category_list,'exam_areas'=>$exam_areas,'roles'=>$roles];


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManageImage $imgService,ExamAreaRequest $request)
    {
        $area = ExamArea::create($request->all());

        // dd($request->all());        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->guessExtension();
            $imgService->upload("area_" . $area->id . '.' . $ext, '', $file);
          }
        // return ['data'=>$file,'image_name'=>"area_".$id];



        //ピボットテーブルの更新
        $role_ids = $request->role_ids;
        $role_ids = array_keys(\array_filter($role_ids,function($element) {
            return !empty($element);
        }));
        // return ['data'=>$role_ids];

        $area->roles()->sync($role_ids);

        // イベントをディスパッチする
        event(new saveModel(["検査エリアの新規作成","create_exam_area",5,"{$area->name}エリアをシステムに登録"]));


        return ['message'=>'検査エリア情報を登録しました。'];   
    }

    public function getImagePath($id){
        $img_path = ManageImage::checkImgExists('area_'.$id);
        if($img_path){
            return $img_path['relative_path'];
        }else{
            return 'area_default.jpg';
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManageImage $imgService,ExamAreaRequest $request, $id)
    {     
        // print_r($request->all());        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->guessExtension();
            $imgService->upload("area_" . $id . '.' . $ext, '', $file);
          }
        // return ['data'=>$file,'image_name'=>"area_".$id];


        $area = ExamArea::findOrFail($id);
        $area->fill($request->all())->save();

        //ピボットテーブルの更新
        $role_ids = $request->role_ids;
        $role_ids = array_keys(\array_filter($role_ids,function($element) {
            return !empty($element);
        }));
        // return ['data'=>$role_ids];    
        
        $area->roles()->sync($role_ids);        

        // イベントをディスパッチする
        event(new saveModel(["検査エリアの更新","update_exam_area",5,"{$area->name}エリア情報を更新"]));

        return ['message'=>'検査エリア情報を更新しました。'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManageImage $imgService, $id)
    {
        $imgService->delete('area_' . $id);

        $area = ExamArea::findOrFail($id);
        $area_name = $area->name;

        $area->delete();

        // イベントをディスパッチする
        event(new saveModel(["検査エリアの削除","delete_exam_area",5,"{$area_name}エリアをシステムから削除"]));        

        return ['message'=>'指定したエリアは削除されました'];
    }    
}
