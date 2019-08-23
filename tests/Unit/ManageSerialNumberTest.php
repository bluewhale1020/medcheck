<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ManageSerialNumber;

use App\ReserveInfo;
use App\Configuration;
use App\ReceptionList;

class ManageSerialNumberTest extends TestCase
{
    use DatabaseTransactions;

    protected $mSNumber;
    protected $reception_list_id;
    protected $reserve_infos;

    public function setup(): void
    {
        parent::setUp();
    //   //データの定義とインサート
        $reception_list = ReceptionList::create([
            'name'=>'test',                     
            'import_date'=>'2019-07-05',                     
            'expiration_date'=>'2019-08-05',                     
            'first_serial_number'=>28,
            'last_serial_number'=>41,
            'max_serial_number'=>55,
        ]);
        $this->reception_list_id = $reception_list->id;

        $config = Configuration::where('name','reception_list_id')->first();
        $config->value = $this->reception_list_id;
        $config->save();
        
        $numbers = [28,29,30,40,41,42,54,55];

        $this->reserve_infos = [];
        foreach ($numbers as $key => $number) {
            $reserve = factory(ReserveInfo::class)->make();
            $reserve->serial_number = $number;
            $reserve->reception_list_id =$this->reception_list_id;
            $reserve->save();
            // $this->reserve_infos[] = $reserve;
        }

        $this->mSNumber = new ManageSerialNumber();


    } 
    public function createRequest($rawData){

        return new Request($rawData);
    }
    
    public function adjustNumbers($reception_id,$request){
        // 28 41 55
        $data = [
            'first_serial_number'=>34,
            'last_serial_number'=>35,
            'max_serial_number'=>39,            
        ];
        $result =  ManageSerialNumber::adjustNumbers($this->reception_list_id,$this->createRequest($data));

        $expected = 28;
        $this->assertEquals($expected,$result->first_serial_number);            
        $expected = 35;
        $this->assertEquals($expected,$result->last_serial_number);            
        $expected = 55;
        $this->assertEquals($expected,$result->max_serial_number);            
    }

    public function testGetMaxSerialNumber(){
        $data = [];
        $result =  ManageSerialNumber::getMaxSerialNumber($this->reception_list_id);

        $expected = 55;
        $this->assertEquals($expected,$result);         
    }
    
    public function testGetSerialNumbers(){
        $data = [];
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>28,'last_serial_number'=>41,'max_serial_number'=>55];
        $this->assertEquals($expected,$result);         
    }

    
    public function testGetReceptionId(){
        $data = [];
        $result =  $this->mSNumber->getReceptionId();

        $expected = $this->reception_list_id;
        $this->assertEquals($expected,$result);         
    }

    // $numbers = [28,29,30,40,41,42,54,55];    
    public function testValidateSerialNumber(){

        // foreach ($this->reserve_infos as $key => $reserve) {
        //     print_r($reserve->all()->toArray());
        //     # code...
        // }        

        $serial_number = 29;
        $reserve_info_id = null;
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 56;
        $this->assertEquals($expected,$result); 
 
        $serial_number = 42;
        $reserve_info_id = null;
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 56;
        $this->assertEquals($expected,$result);         

        
        $serial_number = 55;
        $reserve_info_id = ReserveInfo::where('reception_list_id',$this->reception_list_id)->where('serial_number',$serial_number)->pluck('id')->first();
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 55;
        $this->assertEquals($expected,$result); 
        
        
        $serial_number = 30;
        $reserve_info_id = ReserveInfo::where('reception_list_id',$this->reception_list_id)->where('serial_number',$serial_number)->pluck('id')->first();
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 30;
        $this->assertEquals($expected,$result); 
        
        
        $serial_number = 45;
        $reserve_info_id = null;
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 45;
        $this->assertEquals($expected,$result); 
        
        
        $serial_number = 60;
        $reserve_info_id = null;
        $result =  $this->mSNumber->validateSerialNumber($serial_number,$reserve_info_id);

        $expected = 60;
        $this->assertEquals($expected,$result); 
        
        

    }

    // 28,41,55
    // $numbers = [28,29,30,40,41,42,54,55];   
    public function testDeleteSerialNumber(){

        $serial_number = '';
        $result =  $this->mSNumber->deleteSerialNumber($serial_number);

        $expected = false;
        $this->assertEquals($expected,$result);   
       

        
        $serial_number = 28;
        ReserveInfo::where('serial_number',$serial_number)->delete();        
        $result =  $this->mSNumber->deleteSerialNumber($serial_number);

        $expected = 56;
        $this->assertEquals($expected,$result);
        
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>29,'last_serial_number'=>41,'max_serial_number'=>55];
        $this->assertEquals($expected,$result);          
        
        $serial_number = 55;
        ReserveInfo::where('serial_number',$serial_number)->delete();        
        $result =  $this->mSNumber->deleteSerialNumber($serial_number);

        $expected = 55;
        $this->assertEquals($expected,$result);       
        
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>29,'last_serial_number'=>41,'max_serial_number'=>54];
        $this->assertEquals($expected,$result);          

    }

    // $numbers = [28,29,30,40,41,42,54,55];   
    public function testRegisterSerialNumber(){
        $serial_number = '';
        $result =  $this->mSNumber->registerSerialNumber($serial_number);

        $expected = false;
        $this->assertEquals($expected,$result);   
        
        $serial_number = 35;
        $result =  $this->mSNumber->registerSerialNumber($serial_number);

        $expected = 56;
        $this->assertEquals($expected,$result);
        
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>28,'last_serial_number'=>35,'max_serial_number'=>55];
        $this->assertEquals($expected,$result);  
        

        
        $serial_number = 50;
        $result =  $this->mSNumber->registerSerialNumber($serial_number);

        $expected = 56;
        $this->assertEquals($expected,$result);
        
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>28,'last_serial_number'=>50,'max_serial_number'=>55];
        $this->assertEquals($expected,$result);          
        
        $serial_number = 60;
        $result =  $this->mSNumber->registerSerialNumber($serial_number);

        $expected = 61;
        $this->assertEquals($expected,$result);       
        
        $result =  ManageSerialNumber::getSerialNumbers($this->reception_list_id);

        $expected = ['first_serial_number'=>28,'last_serial_number'=>60,'max_serial_number'=>60];
        $this->assertEquals($expected,$result);          

    }

    public function tearDown(): void
    {
        parent::tearDown();
    } 

}
