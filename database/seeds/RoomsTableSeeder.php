<?php

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('rooms')->delete();
        
        \DB::table('rooms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'room_no' => '101 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-11-26 10:32:02',
            ),
            1 => 
            array (
                'id' => 2,
                'room_no' => '102 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            2 => 
            array (
                'id' => 3,
                'room_no' => '103 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            3 => 
            array (
                'id' => 4,
                'room_no' => '104 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            4 => 
            array (
                'id' => 5,
                'room_no' => '105 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            5 => 
            array (
                'id' => 6,
                'room_no' => '106 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            6 => 
            array (
                'id' => 7,
                'room_no' => '107 รายวัน',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            7 => 
            array (
                'id' => 8,
                'room_no' => '108 เก็บของ',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            8 => 
            array (
                'id' => 9,
                'room_no' => '109 รับแขก',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            9 => 
            array (
                'id' => 10,
                'room_no' => '114 ออฟฟิท',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            10 => 
            array (
                'id' => 11,
                'room_no' => '115 เก็บของ',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            11 => 
            array (
                'id' => 12,
                'room_no' => '116 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            12 => 
            array (
                'id' => 13,
                'room_no' => '117 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            13 => 
            array (
                'id' => 14,
                'room_no' => '118 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            14 => 
            array (
                'id' => 15,
                'room_no' => '119 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            15 => 
            array (
                'id' => 16,
                'room_no' => '120 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            16 => 
            array (
                'id' => 17,
                'room_no' => '121 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            17 => 
            array (
                'id' => 18,
                'room_no' => '122 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            18 => 
            array (
                'id' => 19,
                'room_no' => '201 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            19 => 
            array (
                'id' => 20,
                'room_no' => '202 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            20 => 
            array (
                'id' => 21,
                'room_no' => '203 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            21 => 
            array (
                'id' => 22,
                'room_no' => '204 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            22 => 
            array (
                'id' => 23,
                'room_no' => '205 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            23 => 
            array (
                'id' => 24,
                'room_no' => '206 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            24 => 
            array (
                'id' => 25,
                'room_no' => '207 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            25 => 
            array (
                'id' => 26,
                'room_no' => '208 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            26 => 
            array (
                'id' => 27,
                'room_no' => '209 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            27 => 
            array (
                'id' => 28,
                'room_no' => '211 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            28 => 
            array (
                'id' => 29,
                'room_no' => '214 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            29 => 
            array (
                'id' => 30,
                'room_no' => '215 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            30 => 
            array (
                'id' => 31,
                'room_no' => '216 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            31 => 
            array (
                'id' => 32,
                'room_no' => '217 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            32 => 
            array (
                'id' => 33,
                'room_no' => '218 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            33 => 
            array (
                'id' => 34,
                'room_no' => '219 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            34 => 
            array (
                'id' => 35,
                'room_no' => '220 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            35 => 
            array (
                'id' => 36,
                'room_no' => '221 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            36 => 
            array (
                'id' => 37,
                'room_no' => '222 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            37 => 
            array (
                'id' => 38,
                'room_no' => '301 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            38 => 
            array (
                'id' => 39,
                'room_no' => '302 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            39 => 
            array (
                'id' => 40,
                'room_no' => '303 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            40 => 
            array (
                'id' => 41,
                'room_no' => '304 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            41 => 
            array (
                'id' => 42,
                'room_no' => '305 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            42 => 
            array (
                'id' => 43,
                'room_no' => '306 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            43 => 
            array (
                'id' => 44,
                'room_no' => '307 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            44 => 
            array (
                'id' => 45,
                'room_no' => '308 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            45 => 
            array (
                'id' => 46,
                'room_no' => '309 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            46 => 
            array (
                'id' => 47,
                'room_no' => '311 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            47 => 
            array (
                'id' => 48,
                'room_no' => '314 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            48 => 
            array (
                'id' => 49,
                'room_no' => '315 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            49 => 
            array (
                'id' => 50,
                'room_no' => '316 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            50 => 
            array (
                'id' => 51,
                'room_no' => '317 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            51 => 
            array (
                'id' => 52,
                'room_no' => '318 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            52 => 
            array (
                'id' => 53,
                'room_no' => '319 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            53 => 
            array (
                'id' => 54,
                'room_no' => '320 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            54 => 
            array (
                'id' => 55,
                'room_no' => '321 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            55 => 
            array (
                'id' => 56,
                'room_no' => '322 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            56 => 
            array (
                'id' => 57,
                'room_no' => '401 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            57 => 
            array (
                'id' => 58,
                'room_no' => '402 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            58 => 
            array (
                'id' => 59,
                'room_no' => '403 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            59 => 
            array (
                'id' => 60,
                'room_no' => '404 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            60 => 
            array (
                'id' => 61,
                'room_no' => '405 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            61 => 
            array (
                'id' => 62,
                'room_no' => '406 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            62 => 
            array (
                'id' => 63,
                'room_no' => '407 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            63 => 
            array (
                'id' => 64,
                'room_no' => '408 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            64 => 
            array (
                'id' => 65,
                'room_no' => '409 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            65 => 
            array (
                'id' => 66,
                'room_no' => '411 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            66 => 
            array (
                'id' => 67,
                'room_no' => '414 พัดลม',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            67 => 
            array (
                'id' => 68,
                'room_no' => '415 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            68 => 
            array (
                'id' => 69,
                'room_no' => '416 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            69 => 
            array (
                'id' => 70,
                'room_no' => '417 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            70 => 
            array (
                'id' => 71,
                'room_no' => '418 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            71 => 
            array (
                'id' => 72,
                'room_no' => '419 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            72 => 
            array (
                'id' => 73,
                'room_no' => '420 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            73 => 
            array (
                'id' => 74,
                'room_no' => '421 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            74 => 
            array (
                'id' => 75,
                'room_no' => '422 แอร์',
                'status' => 'available',
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
        ));
        
        
    }
}