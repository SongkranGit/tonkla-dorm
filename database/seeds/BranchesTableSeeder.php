<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\TruncateTable;
use Carbon\Carbon as Carbon;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $table = "branches";

    public function run()
    {
        DB::table($this->table)->truncate();

        $branches = [
            [
                'name'          => 'ต้นกล้า เรสซิเดนซ์',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'          => 'หอพักจักรคำ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'          => 'หอพักต้นธง',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'          => 'โรงแรม',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table($this->table)->insert($branches);
    }
}
