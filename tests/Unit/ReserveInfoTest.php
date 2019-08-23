<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\ReserveInfo;
use Carbon\Carbon;

class ReserveInfoTest extends TestCase
{
    use DatabaseTransactions;

    protected $reception_list_id;
    protected $reserveInfo;
    protected $today;
    protected $checkupdate;


    function convertToObject($array) {
        $object = new \stdClass(); 
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }    


    public function setup(): void
    {
        parent::setUp();

        $this->reception_list_id = 8;

        $this->today = Carbon::today();
        $this->checkupdate = $this->today->format('Y-m-d');
        
        $this->reserveInfo = new ReserveInfo();        

        factory(ReserveInfo::class,6)->create(['check_in'=>false,'complete'=>false,
            'checkup_date'=>$this->checkupdate.' 08:00:00','reception_list_id'=>$this->reception_list_id]);
        factory(ReserveInfo::class,7)->create(['check_in'=>true,'complete'=>true,
            'checkup_date'=>$this->checkupdate.' 09:00:00','reception_list_id'=>$this->reception_list_id]);
        factory(ReserveInfo::class,3)->create(['check_in'=>true,'complete'=>false,
            'checkup_date'=>$this->checkupdate.' 12:00:00','reception_list_id'=>$this->reception_list_id]);
        factory(ReserveInfo::class,4)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 13:00:00','reception_list_id'=>$this->reception_list_id]);
        factory(ReserveInfo::class,5)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 13:30:00','reception_list_id'=>$this->reception_list_id]);

    }

    public function testCreateArrayFromCounts(){

        $sql_result = [
            ['time'=>$this->checkupdate.' 09:00:00','count'=>7],
        ];
        $data = $this->convertToObject($sql_result); 
        $result =  $this->reserveInfo->createArrayFromCounts(Carbon::today(),$data,30);
        // dd($result);
        $expected = [18,[7,0,0,0,0]];
        // $expected = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $this->assertEquals($expected,$result);  
        
        $sql_result = [
            ['time'=>$this->checkupdate.' 09:00:00','count'=>7],
            ['time'=>$this->checkupdate.' 12:00:00','count'=>3],
            ['time'=>$this->checkupdate.' 13:00:00','count'=>4],
            ['time'=>$this->checkupdate.' 13:30:00','count'=>5],
        ];
        $data = $this->convertToObject($sql_result); 
        $result =  $this->reserveInfo->createArrayFromCounts(Carbon::today(),$data,30);

        $expected = [18,[7,0,0,0,0,0,3,0,4,5]];
        // $expected = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,0,3,0,4,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $this->assertEquals($expected,$result);   
        // dd($result);        
        
     
    }
    
    
    public function testGetTimeSeriesData(){
        $result =  $this->reserveInfo->getTimeSeriesData($this->reception_list_id,30);
        
        $expected = [18,[7,0,0,0,0,0,3,0,4,5]];
        // $expected = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,0,3,0,4,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $this->assertEquals($expected,$result);       
    }

    public function testGetReserveStat(){

        $result =  $this->reserveInfo->getReserveStat($this->reception_list_id,30);

        $time_series = [7,0,0,0,0,0,3,0,4,5];
        // $time_series = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,0,3,0,4,5,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        $reserve_count = 25;
        $check_in_count = 19;
        $complete_count = 7;
        $expected = ['count_at_intervals'=>$time_series,'start_time'=> 18,
        'reserve_count'=> $reserve_count,'check_in_count'=> $check_in_count,'complete_count'=> $complete_count];
        $this->assertEquals($expected,$result);

    }

    public function tearDown(): void
    {
        parent::tearDown();
    } 

}
