<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Mockery;
use App\EventList;
use App\Statistic;
use App\ReserveInfo;
use App\SelectItem;
use App\ExamArea;
use App\Configuration;

use DB;
use Carbon\Carbon;

class DashBoardControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $reception_list_id;
    protected $stat_data;
    protected $checkupdate;

    public function setup(): void
    {        
        parent::setUp();
        //   //データの定義とインサート
        $this->reception_list_id = 8;

        $today = Carbon::today();
        $this->checkupdate = $today->format('Y-m-d');


        $config = Configuration::where('name','reception_list_id')->first();
        $config->value = $this->reception_list_id;
        $config->save();      

        $last_event_created_at = '2019-07-26 12:00:00';

        EventList::insert([
            'name'=> "latest",
            'type'=> "stat",
            'level'=> 5,
            'notes'=> "latest rec",
            'created_at'=> $last_event_created_at,
            'updated_at'=> $last_event_created_at,
        ]);

        $this->stat_data = [
            "last_updated_time" => $last_event_created_at,
            "start_time" => null,
            "interval" => "30",
            "reserve_count" => "20",
            "check_in_count" => "15",
            "complete_count" => "5",
            "count_at_intervals" => ["1","2","3"],
            "検便・尿検完了数" => "1",
            "身体計測完了数" => "2",
            "視力完了数" => "3",
            "聴力完了数" => "4",
            "血圧完了数" => "5",
            "採血検査完了数" => "6",
            "胸部XP完了数" => "7",
            "心電図完了数" => "8",
            "診察完了数" => "9",
            "検便・尿検対象者数" => "10",
            "身体計測対象者数" => "11",
            "視力対象者数" => "12",
            "聴力対象者数" => "13",
            "血圧対象者数" => "14",
            "採血検査対象者数" => "15",
            "胸部XP対象者数" => "16",
            "心電図対象者数" => "17",
            "診察対象者数" => "18",
        ];
        
        $stat = new Statistic();
        $stat->saveStatisticsData($last_event_created_at,$this->stat_data);
        
    } 

    private function insertDataForStatIndex(){
        //データの定義とインサート
        DB::table('exam_areas')->insert([
            [
                "name" => "握力",
                "exam_category_id" => 1,
                "height" => 0,
                "weight" => 0,
                "bodyfat_ratio" => 0,
                "abdominal_circumference" => 0,
                "vision_test" => 0,
                "hearing_test" => 0,
                "hearing_test_conv" => 0,
                "physical_examination" => 0,
                "blood_pressure" => 0,
                "urinary_test" => 0,
                "urinary_sediment" => 0,
                "blood_test" => 0,
                "fecaloccult_blood" => 0,
                "electrogram_test" => 0,
                "chest_xray" => 0,
                "stomach_xray" => 0,
                "eye_pressure" => 0,
                "eyeground" => 0,
                "grip_strength" => 1,
                "lung_capacities" => 0,
                "urinary_metabolites" => 0,
            ],     
            [
                "name" => "肺機能",
                "exam_category_id" => 1,
                "height" => 0,
                "weight" => 0,
                "bodyfat_ratio" => 0,
                "abdominal_circumference" => 0,
                "vision_test" => 0,
                "hearing_test" => 0,
                "hearing_test_conv" => 0,
                "physical_examination" => 0,
                "blood_pressure" => 0,
                "urinary_test" => 0,
                "urinary_sediment" => 0,
                "blood_test" => 0,
                "fecaloccult_blood" => 0,
                "electrogram_test" => 0,
                "chest_xray" => 1,
                "stomach_xray" => 0,
                "eye_pressure" => 0,
                "eyeground" => 0,
                "grip_strength" => 0,
                "lung_capacities" => 1,
                "urinary_metabolites" => 0,
            ],            
        ]);

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


        factory(ReserveInfo::class,3)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 15:00:00','reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 1,"lung_capacities" => 0,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });    
        factory(ReserveInfo::class,5)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 15:00:00','reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 2,"lung_capacities" => 0,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });    

        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 15:00:00','reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 1,'chest_xray'=>2,'urinary_metabolites'=>0]));
            });
        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 15:00:00','reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 2,'chest_xray'=>2,'urinary_metabolites'=>0]));
            });
        factory(ReserveInfo::class,1)->create(['check_in'=>true,'complete'=>false,
        'checkup_date'=>$this->checkupdate.' 15:00:00','reception_list_id'=>$this->reception_list_id])
        ->each(function(ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(SelectItem::class)
            ->make(["grip_strength" => 0,"lung_capacities" => 1,'chest_xray'=>0,'urinary_metabolites'=>0]));
            });
    }

    public function testStatIndex(){
        $event_created_at = '2019-07-26 11:00:00';
        $response = $this->call('POST', "api/stat", ['event_created_at'=> $event_created_at]);
        // $response = $this->call('GET', "api/stat/".$event_created_at);

        $response->assertStatus(200);

        $expected = ['stat_data'=>$this->stat_data];

        $responseData = $response->getContent();
        // print_r($responseData);

        $response->assertSee(json_encode($expected));            


        $event_created_at = '2019-07-26 12:00:00';
        $response = $this->call('POST', "api/stat", ['event_created_at'=> $event_created_at]);

        $response->assertStatus(200);

        $expected = ['stat_data'=>$this->stat_data];

        $responseData = $response->getContent();
        // print_r($responseData);

        $response->assertSee(json_encode($expected)); 
        

        $this->insertDataForStatIndex();

        $event_created_at = '2019-07-26 14:00:00';
        $response = $this->call('POST', "api/stat", ['event_created_at'=> $event_created_at]);

        $responseData = $response->getContent();
        print_r($responseData);
        $response->assertStatus(200);

        $time_series = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,7,0,0,0,0,0,3,0,4,5,0,0,11,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

        $reserve_stat = ['count_at_intervals'=>$time_series, 
        'reserve_count'=> 36,'check_in_count'=> 30,'complete_count'=> 7];     

        $stat_data = $reserve_stat + ['握力完了数'=>5, '握力対象者数'=>8] + ['肺機能完了数'=>1, '肺機能対象者数'=>3];
        $save_result = ['result'=>true,'message'=>'統計データを保存しました。'];

        $expected = ['stat_data'=>$stat_data,'save_result'=>$save_result];


        $response->assertSee(json_encode($expected['save_result']));   


        foreach ($expected['stat_data'] as $key => $value) {
            if(\is_array($value)){
                $value = "[". \implode(",",$value) ."]";
            }
            $response->assertSee(json_encode($key).':'.$value);             
        }
      

    }


    public function tearDown(): void
    {
        // Mockery::close();

        parent::tearDown();
    }     

}
