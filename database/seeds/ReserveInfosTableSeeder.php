<?php

use Illuminate\Database\Seeder;

class ReserveInfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('reserve_infos')->truncate();

        // データ挿入
        factory(App\ReserveInfo::class,10)->create()
        ->each(function(App\ReserveInfo $reserve) {
            $reserve->select_item()->save(factory(App\SelectItem::class)->make());
            $reserve->exam_result()->save(factory(App\ExamResult::class)->make());
        });        
        // factory(App\ReserveInfo::class, 10)->create();
    }
}
