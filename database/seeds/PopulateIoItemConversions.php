<?php

use Illuminate\Database\Seeder;
use Flynsarmy\CsvSeeder\CsvSeeder;

class PopulateIoItemConversions extends CsvSeeder
{

    public function __construct()
    {
        $this->table = 'io_item_conversions';
        // $this->csv_delimiter = ',';
        $this->filename = base_path().'/database/seeds/csvs/io_item_conversions.csv';
        // Skipping the CSV header row (Note: A mapping is required if this is done)
        // $this->offset_rows = 1;
        // Specifying which CSV columns to import
        // $this->mapping = [
        //     0 => 'id',
        //     1 => 'name',
        //     2 => 'item_name',
        //     3 => 'tb_name',
        //     4 => 'input_conversion',
        //     5 => 'output_conversion',
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
