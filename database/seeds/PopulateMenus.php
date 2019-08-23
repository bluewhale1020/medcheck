<?php

use Illuminate\Database\Seeder;
use Flynsarmy\CsvSeeder\CsvSeeder;

class PopulateMenus extends CsvSeeder
{
	public function __construct()
	{
        $this->table = 'menus';
        // $this->csv_delimiter = ',';
        $this->filename = base_path().'/database/seeds/csvs/menus.csv';
        // Skipping the CSV header row (Note: A mapping is required if this is done)
        // $this->offset_rows = 1;
        // Specifying which CSV columns to import
        // $this->mapping = [
        //     0 => 'first_name',
        //     1 => 'last_name',
        //     5 => 'age',
        // ];  
        $this->should_trim = true;      
	}

	public function run()
	{
		// Recommended when importing larger CSVs
		// DB::disableQueryLog();

		// Uncomment the below to wipe the table clean before populating
		DB::table($this->table)->truncate();

		parent::run();
	}
}
