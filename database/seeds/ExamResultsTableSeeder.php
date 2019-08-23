<?php

use Illuminate\Database\Seeder;

class ExamResultsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('exam_results')->truncate();

        // データ挿入
        factory(App\ExamResult::class, 10)->create();
    }
}
