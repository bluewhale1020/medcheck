<?php

use Illuminate\Database\Seeder;

class ReceptionListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('reception_lists')->truncate();


        DB::table('reception_lists')->insert([
            [
                'id' => 1,
                'name' => '健診簿１.xlsx',
                'import_date' => '2019-06-05',
                'expiration_date' => '2019-09-05',
                'main_course' => '定期健診',
                'main_kenpo' => true,
                'first_serial_number' => 114,
                'last_serial_number' => 200,
                'max_serial_number' => 200,
                'created_at' => '2019-06-05',
                'updated_at' => '2019-06-05'
            ],
        ]);
    }
}
