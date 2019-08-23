<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\Request;
use Mockery;
use App\Services\TestTargets;
use App\Services\UpdateResult;
use App\ReserveInfo;
use App\ExamResult;
use App\SelectItem;

class ExamAreaControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $test_reserve_id;    

    /**
     * testGetResultIndex test 
     * @param $exam_area_id
     * @return ['reserveInfos'=>$reserveInfos, 'item_list'=>$item_list,'result_infos'=>$result_infos]
     */
    public function testGetResultIndex()
    {
        $exam_area_id = 5;

        $reserveInfos = factory(ReserveInfo::class,2)->states('test_controller')->make()
        ->each(function($model) {
            return $model['progress'] = "実施済み";
        });
        $area_items = ['blood_pressure'];        
        $select_info_ids = [9];
        $retData = [$reserveInfos,$area_items,$select_info_ids,5];

        $params = [
            'page'=> 1,
            'search_key'=>"",
            'status'=>0,
            'first_no'=>"",
            'last_no'=>""
        ];        

        $request = new Request($params);


        $mock = Mockery::mock(TestTargets::class);

        $mock->shouldReceive('getTestTargetInfos')
             ->once()
            //  ->with(5,Mockery::any())
             ->andReturn($retData);

        $this->instance(TestTargets::class, $mock);


        
        $response = $this->call('GET', "api/exam_area/".$exam_area_id."/init",$params);
        // dd($response->getContent());
        $response->assertStatus(200);
        
        $item_list = [];
        $result_infos = [
            "h_blood_pressure"=>
            [
                "id"=> 15,
                "name"=> "h_blood_pressure",
                "name_jp"=> "収縮期血圧",
                "select_item_category"=> "blood_pressure",
                "select_item_info_id"=> 9,
                "unit"=> "mmHg",
                "num_decimal_places"=> 0,
                "options"=> null,
                "min_val"=> 60,
                "max_val"=> 300,
                "m_lower_limit"=> "",
                "m_upper_limit"=> "129",
                "fm_lower_limit"=> "",
                "fm_upper_limit"=> "129",
                "created_at"=> "0000-00-00 00:00:00",
                "updated_at"=> "0000-00-00 00:00:00",
                "exam_result_order"=> 15,
            ],
            "l_blood_pressure"=>
            [
                "id"=> 16,
                "name"=> "l_blood_pressure",
                "name_jp"=> "拡張期血圧",
                "select_item_category"=> "blood_pressure",
                "select_item_info_id"=> 9,
                "unit"=> "mmHg",
                "num_decimal_places"=> 0,
                "options"=> null,
                "min_val"=> 30,
                "max_val"=> 150,
                "m_lower_limit"=> "",
                "m_upper_limit"=> "84",
                "fm_lower_limit"=> "",
                "fm_upper_limit"=> "84",
                "created_at"=> "0000-00-00 00:00:00",
                "updated_at"=> "0000-00-00 00:00:00",
                "exam_result_order"=> 16,
                ]
            ];
            
            // $expected = ['reserveInfos'=>$reserveInfos, 'item_list'=>$item_list,'result_infos'=>$result_infos];

            $responseData = $response->getContent();
            foreach ($reserveInfos as $key => $reserve) {
                $json_reserve = $reserve->toJson();
                $response->assertSee($json_reserve);
            }

            $response->assertSee(json_encode($item_list));            
            $response->assertSee(json_encode($result_infos)); 
    }


    /**
     * testUpdateResult test 
     * @param UpdateResult $updateService,Request $request, $reserve_info_id
     * @return ['exam_result'=>$examResult,'select_item'=>$selectItem,'errors'=>$this->getErrorInfo()]
     */
    public function testUpdateResult()
    {
        $rawData = [
            'fecaloccult_blood'=> 2,
            'urinary_sediment'=>1,          
            'result_list'=>[],
            'select_list'=> [ "fecaloccult_blood", "urinary_test", "urinary_sediment" ],
            ];
            $request = new Request($rawData);

        factory(ReserveInfo::class,1)->create()
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)->make());
            $reserve->exam_result()->save(factory(ExamResult::class)->make());
            $this->test_reserve_id = $reserve->id;
        });

        $rawData = ['fecaloccult_blood'=> 2,'urinary_sediment'=>1,'reserve_info_id'=> $this->test_reserve_id];

        $selectItem = SelectItem::where('reserve_info_id',$this->test_reserve_id)->first();
        $selectItem->fecaloccult_blood = 2;
        $selectItem->urinary_sediment = 1;

        $retData = ['exam_result'=>false,'select_item'=>$selectItem];
        
        $mock = Mockery::mock(UpdateResult::class);

        $mock->shouldReceive('saveResultForm')
                ->once()
                ->with(Mockery::any(),$this->test_reserve_id)
                ->andReturn($retData);

        $this->instance(UpdateResult::class, $mock);


        $response = $this->put("api/exam_area/".$this->test_reserve_id,$rawData);            
        // $response = $this->call('PUT', "api/exam_area/".$this->test_reserve_id,$rawData);
        // dd($response->getContent());
        $response->assertStatus(200);            



        $responseData = $response->getContent();
        foreach ($rawData as $key => $value) {
            # code...
            $response->assertSee('"'.$key.'":'.$value);             
        }
        // $response->assertSee(json_encode($rawData));             
    }

        
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }    
}
