<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Statistic;
use Carbon\Carbon;

use DB;

class StatisticTest extends TestCase
{
    use DatabaseTransactions;

    protected $stat;
    protected $stat_data;
    protected $db_data;

    public function setup(): void
    {
        parent::setUp();
  
        $this->stat = new Statistic();
        $this->stat_data = [
            // "last_updated_time" => '2019-07-26 00:00:00',
            // "start_time" => null,
            "interval" => "30",
            "reserve_count" => "20",
            "check_in_count" => "15",
            "complete_count" => "5",
            "count_at_intervals" => null,
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
        $this->db_data = [
           [
            "name" => "last_updated_time",
            "value" => Carbon::today()->toDateTimeString(),
            "name_jp" => "作成日時",
          ],
            //   [
            //     "name" => "start_time",
            //     "value" => null,
            //     "name_jp" => "開始時間",
            //   ],
          [
            "name" => "interval",
            "value" => "30",
            "name_jp" => "時間間隔",
          ],
          [
            "name" => "reserve_count",
            "value" => "20",
            "name_jp" => "予約人数",
          ],
          [
            "name" => "check_in_count",
            "value" => "15",
            "name_jp" => "受付数",
          ],
          [
            "name" => "complete_count",
            "value" => "5",
            "name_jp" => "完了数",
          ],
          [
            "name" => "count_at_intervals",
            "value" => null,
            "name_jp" => "時間別受付数",
          ],
          [
            "name" => "検便・尿検完了数",
            "value" => "1",
          ],
          [
            "name" => "身体計測完了数",
            "value" => "2",
              ],
          [
            "name" => "視力完了数",
            "value" => "3",
          ],
          [
            "name" => "聴力完了数",
            "value" => "4",
          ],
          [
            "name" => "血圧完了数",
            "value" => "5",
          ],
          [
            "name" => "採血検査完了数",
            "value" => "6",
          ],
          [
            "name" => "胸部XP完了数",
            "value" => "7",
          ],
          [
            "name" => "心電図完了数",
            "value" => "8",
          ],
          [
            "name" => "診察完了数",
            "value" => "9",
          ],
          [
            "name" => "検便・尿検対象者数",
            "value" => "10",
          ],
          [
            "name" => "身体計測対象者数",
            "value" => "11",
          ],
          [
            "name" => "視力対象者数",
            "value" => "12",
          ],
          [
            "name" => "聴力対象者数",
            "value" => "13",
          ],
          [
            "name" => "血圧対象者数",
            "value" => "14",
          ],
          [
            "name" => "採血検査対象者数",
            "value" => "15",
          ],
          [
            "name" => "胸部XP対象者数",
            "value" => "16",
          ],
          [
            "name" => "心電図対象者数",
            "value" => "17",
          ],
          [
            "name" => "診察対象者数",
            "value" => "18",
          ],
        ];

    }

    public function testDeleteAreaCountRecords(){
        $event_created_at = '2019-07-26 00:00:00';        
        $data= $this->stat_data;     
        $result =  $this->stat->saveStatisticsData($event_created_at,$data);

        $area_name = '心電図';

        $this->stat->deleteAreaCountRecords($area_name);
        $record = ["name" => "心電図完了数","value" => "8"];
        $this->assertDatabaseMissing('statistics', $record);        
        $record = ["name" => "心電図対象者数","value" => "17"];
        $this->assertDatabaseMissing('statistics', $record);        
    }

    public function testGetStatisticsData(){
        $event_created_at = Carbon::now()->toDateTimeString();

        $result =  $this->stat->getStatisticsData($event_created_at);

        $this->assertFalse($result);

        $data= $this->stat_data;
        $data['count_at_intervals'] = [1,2,3];        
        $data['start_time'] = null;        
        $result =  $this->stat->saveStatisticsData($event_created_at,$data);

        $result =  $this->stat->getStatisticsData($event_created_at);

        $this->assertTrue(\is_array($result));        

        // print_r($result);
        foreach ($this->stat_data as $idx => $value) {
            # code...
            $this->assertContains($value, $result);         
        }


        $event_created_at = Carbon::today()->addDays(1);

        $result =  $this->stat->getStatisticsData($event_created_at);

        $this->assertFalse($result);        
        
    }

    public function testSaveStatisticsData(){
        // データのクリア error happens (1305 SAVEPOINT trans2 does not exist) due to nested transactions
        // DB::table('statistics')->truncate();

        $event_created_at = Carbon::today()->toDateTimeString();
        $data= $this->stat_data;
        $data['count_at_intervals'] = [1,2,3];


        $result =  $this->stat->saveStatisticsData($event_created_at,$data);

        print_r($result);

        $expected = $this->db_data;
        $expected[5]['value'] = "1,2,3";
        // $this->assertEquals($expected,$result); 
        foreach ($expected as $idx => $record) {
            # code...
            $this->assertDatabaseHas('statistics', $record);         
        }
    }



    public function tearDown(): void
    {
        parent::tearDown();
    } 

}
