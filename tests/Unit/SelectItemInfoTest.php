<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SelectItemInfo;

class SelectItemInfoTest extends TestCase
{

    protected $selectItemInfo;

    public function setup(): void
    {
      parent::setUp();

    //   $this->selectItemInfo = new SelectItemInfo();
    } 

    /**
     * testGetNames unit test
     *
     * @return void
     */
    public function testGetNames()
    {
        $ids = [1,2,3];
        // print_r($selectItem);
        $result =  SelectItemInfo::getNames($ids);
        // $result =  $this->selectItemInfo->getNames($ids);

        $expected = ['height','weight','abdominal_circumference'];
        print_r($result);
        $this->assertEquals($expected,$result); 

        $ids = [];
        // print_r($selectItem);
        $result =  SelectItemInfo::getNames($ids);
        // $expected = ['height','weight','abdominal_circumference'];
        print_r($result);
        $this->assertFalse($result);        
    }
    /**
     * testGetIds unit test
     *
     * @return void
     */
    public function testGetIds()
    {
        $names = ['height','weight','abdominal_circumference'];
        // print_r($selectItem);
        $result =  SelectItemInfo::getIds($names);
        // $result =  $this->selectItemInfo->getIds($names);
        
        $expected= [1,2,3];
        print_r($result);
        $this->assertEquals($expected,$result); 
    }

    /**
     * testGetAreaItemList unit test
     * 検査エリアの検査項目の英語・日本語リストを返す
     * @return void
     */
    public function testGetAreaItemList()
    {
        $area_items = ['height','weight','abdominal_circumference'];
        // print_r($selectItem);
        $result =  SelectItemInfo::getAreaItemList($area_items);
        // $result =  $this->selectItemInfo->getIds($names);
        
        $expected= ['height'=>'身長','weight'=>'体重','abdominal_circumference'=>'腹囲'];
        print_r($result);
        $this->assertEquals($expected,$result); 
    }

    /**
     * testGetTableColumns unit test
     *
     * @return void
     */
    public function testGetTableColumns()
    {

        $result =  SelectItemInfo::getTableColumns();

        $expected = ['身長','体重','体脂肪率','腹囲','視力','聴力オージオ','聴力会話法','診察','血圧','尿検査','尿検査タイプ','尿沈渣','血液検査',
        '血液検査タイプ','便潜血','心電図','胸部X線','胃部X線','眼圧','眼底','握力','肺機能','尿代謝物','尿中メチル馬尿酸',
        '尿中N-メチルホルムアミド','マンデル酸','尿中トリクロル酢酸','尿中馬尿酸','尿中2、5-ヘキサンジオン','ホルムアルデヒド','粉じん','鉛',
        '電離放射線','血清インジウム'];
        print(\implode(',',$result->toArray()));
        $this->assertEquals($expected,$result->toArray()); 
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }      
}
