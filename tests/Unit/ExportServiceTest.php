<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ExportService;
use App\Account;
use App\ReserveInfo;
use App\SelectItem;
use App\IoItemConversion;

class ExportServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $export;

    public function setup(): void
    {
      parent::setUp();

      $this->export = new ExportService();
    } 

    /**
     * A unit test testConv_selectitem_into_dot
     *
     * @return void
     */
    public function testConv_selectitem_into_dot(){
        $itemData = 1;

        $result =  $this->export->conv_selectitem_into_dot($itemData);

        $expected = '●';
        $this->assertEquals($expected,$result);   

        $itemData = 2;

        $result =  $this->export->conv_selectitem_into_dot($itemData);

        $expected = '●';
        $this->assertEquals($expected,$result);

        $itemData = 0;

        $result =  $this->export->conv_selectitem_into_dot($itemData);

        $expected = '';
        $this->assertEquals($expected,$result);         
    }




    /**
     * A unit test testFunc_output_hearing
     *
     * @return void
     */
    public function testFunc_output_hearing()
    {
        // 入力データ			     期待結果	
        // hearing_test,1			オージオ		
        // hearing_test_conv,1		会話法	
        $columnName = 'hearing_test';   
        $itemValue = 1;

        $result =  $this->export->func_output_hearing($columnName,$itemValue);

        $expected = 'オージオ';
        $this->assertEquals($expected,$result);         


        $columnName = 'hearing_test_conv';
        $itemValue = 1;

        $result =  $this->export->func_output_hearing($columnName,$itemValue);

        $expected = '会話法';
        $this->assertEquals($expected,$result);         
        
        $columnName = 'hearing_test';   
        $itemValue = 0;

        $result =  $this->export->func_output_hearing($columnName,$itemValue);

        $this->assertFalse($result);         


        $columnName = 'hearing_test_conv';
        $itemValue = 0;

        $result =  $this->export->func_output_hearing($columnName,$itemValue);

        $this->assertFalse($result); 
    }

    /**
     * A unit test testFunc_check_test_type
     *
     * @return void
     */
    public function testFunc_check_test_type()
    {
        // 入力データ			                    期待結果	
        // blood_test_type,A+E			            A+E		
        // urinary_test_type,蛋白+糖			    蛋白・糖	

        $columnName = 'blood_test_type';   
        $itemValue = 'A+E';

        $result =  $this->export->func_output_test_type($columnName,$itemValue);

        $expected = 'A+E';
        $this->assertEquals($expected,$result);
        
        $columnName = 'urinary_test_type';   
        $itemValue = '蛋白+糖';

        $result =  $this->export->func_output_test_type($columnName,$itemValue);

        $expected = '蛋白・糖';
        $this->assertEquals($expected,$result);
        
        $columnName = 'blood_test';   
        $itemValue = 1;

        $result =  $this->export->func_output_test_type($columnName,$itemValue);

        $this->assertFalse($result);

    }


    /**
     * A unit test testSortData
     *
     * @return void
     */
    public function testSortData()
    {
        $columns = IoItemConversion::getTableColumns()->toArray();
        print_r($columns);

        factory(ReserveInfo::class,3)->create([
            'notes'=>'testSortData'
        ])
        ->each(function(ReserveInfo $reserve) {
        $reserve->select_item()->save(factory(SelectItem::class)->make());
        });        

        $reserveInfos = ReserveInfo::where('notes','testSortData')->get();




        
        $sortedData =  $this->export->sortData($reserveInfos);
        
        foreach ($sortedData as $key => $record) {

            print_r($record);

            $idx = \array_search("性別",$columns);

            // assertContains($val, $array)
            $this->assertTrue(\in_array($record[$idx],["男","女"]));
            
            
            $idx = \array_search("受診予定日",$columns);
            
            // assertContains($val, $array)
            $this->assertEquals('2019-06-11',$record[$idx]);
            
            $idx = \array_search("予約時間",$columns);
            
            // assertContains($val, $array)
            $this->assertEquals('12:30:00',$record[$idx]);


            $idx = \array_search("コース",$columns);

            // assertContains($val, $array)
            $this->assertEquals('定期健診',$record[$idx]);


            $idx = \array_search("身長",$columns);

            // assertContains($val, $array)
            $this->assertTrue(\in_array($record[$idx],[0,1,2]));


            $idx = \array_search("尿検査",$columns);
            
            // assertContains($val, $array)
            $this->assertTrue(\in_array($record[$idx],[0,1,2]));


            $idx = \array_search("尿検査2",$columns);
            
            // assertContains($val, $array)
            $this->assertContains($record[$idx],['糖・蛋白・潜血','糖・蛋白']);
            
            
            $idx = \array_search("血液検査",$columns);

            $this->assertContains($record[$idx],['E+F','A+B+C']);


            
        }

    }









}
