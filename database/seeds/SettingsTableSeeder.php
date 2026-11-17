<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'branch_id' => 1,
                'key' => 'electrict_price_per_unit',
                'value' => '8',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'branch_id' => 1,
                'key' => 'water_price_per_unit',
                'value' => '21',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'branch_id' => 1,
                'key' => 'room_rate_daily',
                'value' => '400',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'branch_id' => 1,
                'key' => 'room_rate_monthly_fan',
                'value' => '3000',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'branch_id' => 1,
                'key' => 'vat',
                'value' => '7',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            5 => 
            array (
                'id' => 6,
                'branch_id' => 1,
                'key' => 'vat_special',
                'value' => '7',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'branch_id' => 1,
                'key' => 'extra_1',
                'value' => '200',
                'locale' => NULL,
                'extra_key' => 'ค่าอินเตอร์เนต',
                'extra_sort_no' => 1,
                'is_extra' => 1,
                'is_use_calculate_vat' => 0,
            ),
            7 => 
            array (
                'id' => 8,
                'branch_id' => 1,
                'key' => 'extra_2',
                'value' => '750',
                'locale' => NULL,
                'extra_key' => 'ค่าเฟอร์นิเจอร์',
                'extra_sort_no' => 2,
                'is_extra' => 1,
                'is_use_calculate_vat' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'branch_id' => 1,
                'key' => 'extra_3',
                'value' => '400',
                'locale' => NULL,
                'extra_key' => 'ค่าทีวี',
                'extra_sort_no' => 3,
                'is_extra' => 1,
                'is_use_calculate_vat' => 0,
            ),
            9 => 
            array (
                'id' => 10,
                'branch_id' => 1,
                'key' => 'room_rate_monthly_air',
                'value' => '2250',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            10 => 
            array (
                'id' => 11,
                'branch_id' => 1,
                'key' => 'room_rate_monthly_house',
                'value' => '2500',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            11 => 
            array (
                'id' => 12,
                'key' => 'vat_special_property',
                'value' => '12.5',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            12 => 
            array (
                'id' => 13,
                'key' => 'extra_4',
                'value' => '400',
                'locale' => NULL,
                'extra_key' => 'ตู้เย็น',
                'extra_sort_no' => 4,
                'is_extra' => 1,
                'is_use_calculate_vat' => 0,
            ),
            13 => 
            array (
                'id' => 14,
                'key' => 'extra_5',
                'value' => '100',
                'locale' => NULL,
                'extra_key' => 'ค่าทำความสะอาด',
                'extra_sort_no' => 5,
                'is_extra' => 1,
                'is_use_calculate_vat' => 0,
            ),
            14 => 
            array (
                'id' => 15,
                'key' => 'room_rate_monthly_air_2',
                'value' => '2500',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
            15 => 
            array (
                'id' => 16,
                'key' => 'room_rate_monthly_fan_2',
                'value' => '2000',
                'locale' => NULL,
                'extra_key' => NULL,
                'extra_sort_no' => NULL,
                'is_extra' => 0,
                'is_use_calculate_vat' => 0,
            ),
        ));
        
        
    }
}