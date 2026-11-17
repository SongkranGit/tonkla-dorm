<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EquipmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('equipments')->truncate();
        
        DB::table('equipments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'equipment_no' => 'A01',
                'name' => 'TV',
                'description' => 'TV Description',
                'total_quantity' => 10,
                'available_quantity' => 10,
                'created_at' => '2017-10-27 01:08:02',
                'updated_at' => '2017-10-27 01:08:02',
            ),
            1 => 
            array (
                'id' => 2,
                'equipment_no' => 'A02',
                'name' => 'DVD Player',
                'description' => 'DVD Player Description',
                'total_quantity' => 5,
                'available_quantity' => 5,
                'created_at' => '2017-10-27 01:08:20',
                'updated_at' => '2017-10-27 01:08:20',
            ),
            2 => 
            array (
                'id' => 3,
                'equipment_no' => 'A03',
                'name' => 'Sofa',
                'description' => 'Sofa Description',
                'total_quantity' => 10,
                'available_quantity' => 10,
                'created_at' => '2017-10-27 01:08:38',
                'updated_at' => '2017-10-27 01:08:38',
            ),
        ));
        
        
    }
}