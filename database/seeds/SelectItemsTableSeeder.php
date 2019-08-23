<?php

use Illuminate\Database\Seeder;

class SelectItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('select_items')->truncate();

        // データ挿入
        factory(App\SelectItem::class, 10)->create();
    }
}
