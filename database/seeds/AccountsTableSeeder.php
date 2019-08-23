<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('accounts')->truncate();

        $faker = Faker::create('ja_JP');
        // データ挿入
        factory(App\Account::class, 10)->create();



    }
}
