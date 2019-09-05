<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TestTargets;
use App\Account;
use App\ReserveInfo;
use App\SelectItem;

class TestTargetsTest extends TestCase
{
    use DatabaseTransactions;

    public $test_targets;
    public $test_reserve_id;
 
    public function setup(): void
    {
      parent::setUp();

        
      $this->test_targets = new TestTargets();
    }


    /**
     * testCheckExamResultExists unit test
     * @param $reserveInfos
     * @return $reserveInfos
     */
    public function testCheckExamResultExists()
    {
        factory(ReserveInfo::class,1)->create()
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)->make());
            $this->test_reserve_id = $reserve->id;
        });     
        
        $reserveInfos = ReserveInfo::with(['select_item','exam_result'])->where('id', $this->test_reserve_id)->get();

        $result =  $this->test_targets->checkExamResultExists($reserveInfos);


        $this->assertFalse($result);         
        
    }

    /**
     * testModifyReserve unit test
     * @param $area_items,$reserveInfos
     * @return void
     */
    public function testModifyReserve()
    {
        factory(ReserveInfo::class,1)->create()
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)->make());
            $this->test_reserve_id = $reserve->id;
        });        

        $reserveInfos = ReserveInfo::with('select_item')->where('id', $this->test_reserve_id)->get();
        foreach ($reserveInfos as $key => $reserve) {
            $reserveInfos[$key]->select_item['height'] = 1;
        }

        $area_items = ['height'];
        $result =  $this->test_targets->modifyReserve($area_items,$reserveInfos);


        $progress = "未実施";

        $this->assertEquals($progress,$result[0]['progress']); 

        foreach ($reserveInfos as $key => $reserve) {
            $reserveInfos[$key]->select_item['weight'] = 1;
            $reserveInfos[$key]->select_item['bodyfat_ratio'] = 2;
        }        

        $area_items = ['weight','bodyfat_ratio'];
        $result =  $this->test_targets->modifyReserve($area_items,$reserveInfos);


        $progress = "一部実施";
        print($result[0]['progress']);
        $this->assertEquals($progress,$result[0]['progress']); 

        foreach ($reserveInfos as $key => $reserve) {
            $reserveInfos[$key]->select_item['bodyfat_ratio'] = 2;
            $reserveInfos[$key]->select_item['vision_test'] = 2;
        }         
     

        $area_items = ['bodyfat_ratio','vision_test'];
        $result =  $this->test_targets->modifyReserve($area_items,$reserveInfos);


        $progress = "実施済み";
        print($result[0]['progress']);
        $this->assertEquals($progress,$result[0]['progress']); 


        $area_items = [];
        $result =  $this->test_targets->modifyReserve($area_items,$reserveInfos);
        print_r($result);
        $this->assertFalse($result);        
    }

    /**
     * testConvUrinaryTestType unit test
     * @param $type
     * @return $item_names
     */
    public function testConvUrinaryTestType()
    {
        $type = "蛋白+糖+潜血";
        $result = $this->test_targets->convUrinaryTestType($type);
        
        $expected = ['urinary_protein','urinary_sugar','urinary_blood'];
        $this->assertEquals($expected,$result);       
    }



    /**
     * testGetTestTargetInfos unit test
     * @param $exam_area_id,$request
     * @return [$reserveInfos,$area_items,$select_info_ids]
     */
    public function testGetTestTargetInfos()
    {
      
        $reception_id = 4;
        $exam_area_id = 5;

        list($reserveInfos,$area_items,$select_info_ids) =  
        $this->test_targets->getTestTargetInfos($exam_area_id,$reception_id);
        
        $progress = ["未実施","一部実施", "実施済み"];
        $this->assertContains($reserveInfos[0]['progress'],$progress);
        
        $expected = ['blood_pressure'];
        $this->assertEquals($expected,$area_items);       
        
        $expected = [9];
        $this->assertEquals($expected,$select_info_ids);  
        
        
        $exam_area_id = 4;

        list($reserveInfos,$area_items,$select_info_ids) =  
        $this->test_targets->getTestTargetInfos($exam_area_id,$reception_id);
        print_r($area_items);
        print_r($select_info_ids);
        $this->assertContains($reserveInfos[0]['progress'],$progress);
        
        $expected = ['hearing_test','hearing_test_conv'];
        $this->assertEquals($expected,$area_items);       
        
        $expected = [6,7];
        $this->assertEquals($expected,$select_info_ids); 
        
        
        $exam_area_id = 0;

        $result =  
        $this->test_targets->getTestTargetInfos($exam_area_id,$reception_id);
        
        $this->assertEquals([null,null,null,null],$result);
  
    }



    public function tearDown(): void
    {
        parent::tearDown();
    }

}
