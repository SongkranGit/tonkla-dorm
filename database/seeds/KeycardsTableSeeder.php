<?php

use Illuminate\Database\Seeder;

class KeycardsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('keycards')->delete();
        
        \DB::table('keycards')->insert(array (
            0 => 
            array (
                'id' => 1,
                'branch_id' => 1,
                'key_card_no' => '123456',
                'room_id' => 3,
                'sort_no' => 0,
                'taken_date' => '2017-09-16 00:00:00',
                'created_at' => '2017-09-16 00:00:00',
                'updated_at' => '2017-09-16 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'branch_id' => 1,
                'key_card_no' => '654321',
                'room_id' => 3,
                'sort_no' => 1,
                'taken_date' => '2017-09-16 00:00:00',
                'created_at' => '2017-09-16 00:00:00',
                'updated_at' => '2017-09-16 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'branch_id' => 1,
                'key_card_no' => '999999',
                'room_id' => 3,
                'sort_no' => 3,
                'taken_date' => '2017-09-16 00:00:00',
                'created_at' => '2017-09-16 00:00:00',
                'updated_at' => '2017-09-16 00:00:00',
            ),
        ));
        
        
    }
}