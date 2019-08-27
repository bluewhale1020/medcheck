<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // データのクリア
        DB::table('users')->truncate();

        // データ挿入
        App\User::create([
            'name' => 'admin',
            'role_id'=>1,
            'email' => 'admin@postmaster.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),        
        ]);
        // factory(App\User::class, 10)->create();
    }
}
