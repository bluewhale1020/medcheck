<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\Request;
use App\Services\UpdateResult;

use App\ExamResult;
use App\SelectItem;
use App\ReserveInfo;

class UpdateResultTest extends TestCase
{
    use DatabaseTransactions;

    protected $update;
    protected $request1;
    protected $request2;
    protected $request3;
    protected $request4;
    protected $test_reserve_id;

    public function setup(): void
    {
      parent::setUp();

      $this->update = new UpdateResult();

      $rawData = [
        'fecaloccult_blood'=> 2,
        'urinary_sediment'=>1,          
        'reserve_info_id'=> 37,
        'result_list'=>[],
        'select_list'=> [ "fecaloccult_blood", "urinary_test", "urinary_sediment" ],
      ];
      $this->request1 = new Request($rawData);

      $rawData = [
        'l_hearing_1000hz'=> "所見なし",
        'l_hearing_4000hz'=> "所見あり",
        'r_hearing_1000hz'=> "所見なし",
        'r_hearing_4000hz'=> "所見なし",
        'reserve_info_id'=> 33,
        'result_list'=>["r_hearing_1000hz","l_hearing_1000hz","r_hearing_4000hz","l_hearing_4000hz","hearing_on_conv"],
        'select_list'=> []         
      ];
      $this->request2 = new Request($rawData);


      $rawData = [
        'blood_test'=> 2,
        'reserve_info_id'=> 33,
        'result_list'=>[],
        'select_list'=>[ "blood_test" ]
      ];
      $this->request3 = new Request($rawData);

      $rawData = [
        'aewf'=> 2,
        'w'=> 37,
        'rrr'=>1,          
        'result_list'=>[],
        'select_list'=> [ "fecaloccult_blood", "urinary_test", "urinary_sediment" ],
      ];

      $this->request4 = new Request($rawData);


      factory(ReserveInfo::class,1)->create()
      ->each(function(ReserveInfo $reserve) {
          $reserve->select_item()->save(factory(SelectItem::class)->make());
          $reserve->exam_result()->save(factory(ExamResult::class)->make());
          $this->test_reserve_id = $reserve->id;
      });

    } 

    public function createRequest($rawData){

        $rawData['reserve_info_id']=$this->test_reserve_id;        
        return new Request($rawData);
    }

    /**
     * A unit test testSortRequestData
     *
     * @return void
     */
    public function testSortRequestData()
    {

       #1
        list($resultRequest,$selectRequest) =  $this->update->sortRequestData($this->request1,$this->test_reserve_id);

        $expected =$this->createRequest(
            ['fecaloccult_blood'=> 2,'urinary_sediment'=>1]);

        // print_r($result);
        $this->assertNull($resultRequest);
        $this->assertEquals($expected,$selectRequest);
       
        #2
        list($resultRequest,$selectRequest) =  $this->update->sortRequestData($this->request2,$this->test_reserve_id);

        $expected =$this->createRequest(
            [
                'l_hearing_1000hz'=> "所見なし",
                'l_hearing_4000hz'=> "所見あり",
                'r_hearing_1000hz'=> "所見なし",
                'r_hearing_4000hz'=> "所見なし",                
            ]);

        // print_r($result);
        $this->assertEquals($expected,$resultRequest);
        $expected =$this->createRequest(['hearing_test' => 2]);        
        $this->assertEquals($expected,$selectRequest);
     
        
        #3
        list($resultRequest,$selectRequest) =  $this->update->sortRequestData($this->request3,$this->test_reserve_id);

        $expected =$this->createRequest(
            ['blood_test'=> 2]);

        // print_r($result);
        $this->assertNull($resultRequest);
        $this->assertEquals($expected,$selectRequest);
        
        #4
        list($resultRequest,$selectRequest) =  $this->update->sortRequestData($this->request4,$this->test_reserve_id);

        // print_r($result);
        $this->assertNull($resultRequest);
        $this->assertNull($selectRequest);
        



    }

    /**
     * A unit test testSaveData
     *
     * @return void
     */    
    public function testSaveData(){
        // $expected = ['exam_result'=>$examResult,'select_item'=>$selectItem,'errors'=>$this->getErrorInfo()]
        #1
        $resultRequest = null;
        $selectRequest =$this->createRequest(
            ['fecaloccult_blood'=> 2,'urinary_sediment'=>1]);
        
        $result =  $this->update->saveData($resultRequest, $selectRequest,$this->test_reserve_id);
        
        $this->assertFalse($result['exam_result']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);

        $select_items = $selectRequest->all();
        
        $this->assertDatabaseHas('select_items',$select_items);         
        
        #2
        $resultRequest =$this->createRequest([
            'l_hearing_1000hz'=> "所見なし",
            'l_hearing_4000hz'=> "所見あり",
            'r_hearing_1000hz'=> "所見なし",
            'r_hearing_4000hz'=> "所見なし",                
        ]);
        $selectRequest = null;
        
        $result =  $this->update->saveData($resultRequest, $selectRequest,$this->test_reserve_id);
        
        // print_r($result['errors']);
        $this->assertInstanceOf(ExamResult::class, $result['exam_result']);
        $this->assertFalse($result['select_item']);

        $exam_results = $resultRequest->all();
        
        $this->assertDatabaseHas('exam_results',$exam_results);         

        #3
        $resultRequest = null;
        $selectRequest =$this->createRequest(
            ['blood_test'=> 2]);
        
        $result =  $this->update->saveData($resultRequest, $selectRequest,$this->test_reserve_id);
        
        // print_r($result['errors']);
        $this->assertFalse($result['exam_result']);
        $this->assertInstanceOf(SelectItem::class, $result['select_item']);

        $select_items = $selectRequest->all();
        
        $this->assertDatabaseHas('select_items',$select_items);         

        #4
        $resultRequest = null;
        $selectRequest =null;
        
        $result =  $this->update->saveData($resultRequest, $selectRequest,$this->test_reserve_id);
        
        // print_r($result);
        $this->assertFalse($result['exam_result']);
        $this->assertFalse($result['select_item']);

    }    

    /**
     * A unit test testSaveResultForm
     *
     * @return void
     */
    public function testSaveResultForm(){
               
       #1
       $result =  $this->update->saveResultForm($this->request1,$this->test_reserve_id);

       $expected =$this->createRequest(
           ['fecaloccult_blood'=> 2,'urinary_sediment'=>1]);
            // print_r($result);
           $this->assertFalse($result['exam_result']);
           $this->assertInstanceOf(SelectItem::class, $result['select_item']);
   
           $select_items = $expected->all();
           
           $this->assertDatabaseHas('select_items',$select_items); 
      
       #2
       $result =  $this->update->saveResultForm($this->request2,$this->test_reserve_id);

       $expected =$this->createRequest(
           [
               'l_hearing_1000hz'=> "所見なし",
               'l_hearing_4000hz'=> "所見あり",
               'r_hearing_1000hz'=> "所見なし",
               'r_hearing_4000hz'=> "所見なし",                
           ]);

           $this->assertInstanceOf(ExamResult::class, $result['exam_result']);
           $this->assertInstanceOf(SelectItem::class, $result['select_item']);
   
           $exam_results = $expected->all();
           
           $this->assertDatabaseHas('exam_results',$exam_results);          
       
       #3
       $result =  $this->update->saveResultForm($this->request3,$this->test_reserve_id);

       $expected =$this->createRequest(
           ['blood_test'=> 2]);

           $this->assertFalse($result['exam_result']);
           $this->assertInstanceOf(SelectItem::class, $result['select_item']);
   
           $select_items = $expected->all();
           
           $this->assertDatabaseHas('select_items',$select_items);
       
       #4
       $result =  $this->update->saveResultForm($this->request4,$this->test_reserve_id);

       $expected = ['exception'=>true,'message'=>["保存するデータがありません！"]];

       $this->assertEquals($expected,$result);  


    }    


    public function testMergeSelectData(){

        $selectItems = [
            "urinary_test"=>2,
            "aweafa"=>1,           
        ];
        $selectData = [
            "urinary_test"=>1,
            "ewfew"=>1,           
        ];

        $result = $this->update->mergeSelectData($selectItems, $selectData);
        $expected = [
            "urinary_test"=>2,
            "aweafa"=>1,
            "ewfew"=>1,            
        ];
        $this->assertEquals($expected,$result);

        $selectItems = [
            "urinary_test"=>1,
            "aweafa"=>1,           
        ];
        $selectData = [
            "urinary_test"=>2,
            "ewfew"=>1,           
        ];

        $result = $this->update->mergeSelectData($selectItems, $selectData);
        $expected = [
            "urinary_test"=>2,
            "aweafa"=>1,
            "ewfew"=>1,            
        ];
        $this->assertEquals($expected,$result);

        $selectItems = [
            "urinary_test"=>1,
            "aweafa"=>1,           
        ];
        $selectData = [
            "urinary_test"=>1,
            "ewfew"=>1,           
        ];

        $result = $this->update->mergeSelectData($selectItems, $selectData);
        $expected = [
            "urinary_test"=>1,
            "aweafa"=>1,
            "ewfew"=>1,            
        ];
        $this->assertEquals($expected,$result);
    }

    public function testGetSelectItemsFromResultData(){

        $resultData = [
            "l_hearing_1000hz"=>'',
            "r_hearing_1000hz"=>'',
            "r_hearing_4000hz"=>'',
            "l_hearing_4000hz"=>'',
            "hearing_on_conv"=>''            
        ];

        $result = $this->update->getSelectItemsFromResultData($resultData);
        $expected = [
            'hearing_test'=>1,
            'hearing_test_conv' =>1
        ];
        $this->assertEquals($expected,$result);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }     


}
