<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MessageBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('message_boards')->truncate();

        $faker = Faker::create('ja_JP');
        // データ挿入
        factory(App\MessageBoard::class, 10)->create();
    }
}
