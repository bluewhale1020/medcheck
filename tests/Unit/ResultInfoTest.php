<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ResultInfo;

class ResultInfoTest extends TestCase
{

    protected $resultInfo;

    public function setup(): void
    {
      parent::setUp();

    //   $this->resultInfo = new ResultInfo();
    } 

    /**
     * testGetTableColumns unit test
     *
     * @return void
     */
    public function testGetTableColumns()
    {

        $result =  ResultInfo::getTableColumns();

        $expected = ['胸部腹部所見','身長','体重','体脂肪率','腹囲','裸眼右','裸眼左','矯正右','矯正左','聴力右1KHz','聴力左1KHz',
        '聴力右4KHz','聴力左4KHz','聴力会話法','収縮期血圧','拡張期血圧','蛋白定性','糖定性','ウロビリノーゲン','pH','潜血反応','眼圧右',
        '眼圧左','肺活量','１秒率','１秒量','握力右','握力左','胸部X線番号','胃部X線番号','心電図番号','眼底番号'];
        print(\implode(',',$result->toArray()));
        $this->assertEquals($expected,$result->toArray()); 
    }

    /**
     * testGetItemsFromSelectItemIDs unit test
     *
     * @return void
     */
    public function testGetItemsFromSelectItemIDs()
    {
        $ids = [1,2];

        $result =  ResultInfo::getItemsFromSelectItemIDs($ids);

        // print_r($result);
        // $this->assertEquals($expected,$result); 
        $this->assertInstanceOf(ResultInfo::class,$result['height']['height']); 
        $this->assertInstanceOf(ResultInfo::class,$result['weight']['weight']); 

        $ids = [];
        // print_r($selectItem);
        $result =  ResultInfo::getItemsFromSelectItemIDs($ids);
        // $expected = ['height','weight','abdominal_circumference'];
        // print_r($result);
        $this->assertEquals([],$result);        
    }

    /**
     * testGetSelectItemsFromResultNames unit test
     *
     * @return void
     */
    public function testGetSelectItemsFromResultNames()
    {
        $resultData = [
            "l_hearing_1000hz"=>'所見なし',
            "r_hearing_1000hz"=>'所見なし',
            "r_hearing_4000hz"=>'所見なし',
            "l_hearing_4000hz"=>'所見なし',
            "hearing_on_conv"=>'',            
        ];
        $result =  ResultInfo::getSelectItemsFromResultNames($resultData);

        $expected = ['hearing_test'=>2,'hearing_test_conv'=>1];
        $this->assertEquals($expected,$result); 


        $resultData = [
            "l_hearing_1000hz"=>'所見なし',
            "r_hearing_1000hz"=>'',
            "r_hearing_4000hz"=>'所見なし',
            "l_hearing_4000hz"=>'',
            "hearing_on_conv"=>'所見なし',            
        ];
        $result =  ResultInfo::getSelectItemsFromResultNames($resultData);

        $expected = ['hearing_test'=>2,'hearing_test_conv'=>2];
        $this->assertEquals($expected,$result); 


        $resultData = [
            "l_hearing_1000hz"=>'',
            "r_hearing_1000hz"=>'',
            "r_hearing_4000hz"=>'',
            "l_hearing_4000hz"=>'',
            "hearing_on_conv"=>'',            
        ];
        $result =  ResultInfo::getSelectItemsFromResultNames($resultData);

        $expected = ['hearing_test'=>1,'hearing_test_conv'=>1];
        $this->assertEquals($expected,$result); 


        $resultData = [
            "findings_chestabdomen"=>'',            
        ];
        $result =  ResultInfo::getSelectItemsFromResultNames($resultData);
        print_r($result);
        $expected = ['physical_examination'=>1];
        $this->assertEquals($expected,$result); 

        $resultData = [];
        $result =  ResultInfo::getItemsFromSelectItemIDs($resultData);

        $this->assertEquals([],$result);         
    }
    public function tearDown(): void
    {
        parent::tearDown();
    } 
}
