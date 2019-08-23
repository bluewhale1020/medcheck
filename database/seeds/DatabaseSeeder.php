<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        
        $this->call(PopulateConfigurations::class);
        $this->call(PopulateIoItemConversions::class);
        $this->call(PopulateExamCategories::class);
        $this->call(PopulateRoles::class);
        $this->call(PopulateMenus::class);
        $this->call(PopulateStatistics::class);
        $this->call(PopulateExamAreas::class);
        $this->call(PopulateSpecialItems::class);

    }
}
