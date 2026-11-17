<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'branch_id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('Manao100%'),
                'remember_token' => NULL,
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            1 => 
            array (
                'id' => 2,
                'branch_id' => 1,
                'username' => 'member',
                'email' => 'member@example.com',
                'password' => bcrypt('secret'),
                'remember_token' => NULL,
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            2 => 
            array (
                'id' => 3,
                'branch_id' => 1,
                'username' => 'noon7425',
                'email' => 'noon7425@example.com',
                'password' => bcrypt('053561248'),
                'remember_token' => NULL,
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            3 => 
            array (
                'id' => 4,
                'branch_id' => 1,
                'username' => 'tonklaresidence',
                'email' => 'tonklaresidence@example.com',
                'password' => bcrypt('053510255'),
                'remember_token' => NULL,
                'created_at' => '2017-08-16 01:25:09',
                'updated_at' => '2017-08-16 01:25:09',
            ),
            4 =>
                array (
                    'id' => 5,
                    'branch_id' => 1,
                    'username' => 'owner',
                    'email' => 'owner@example.com',
                    'password' => '$2y$12$JRCAP/AUnWw0yDK3uxhJz.6cHFn8L1hsvYMoFOuZlYAnGz1m5MCT2',
                    'remember_token' => NULL,
                    'created_at' => '2017-08-16 01:25:09',
                    'updated_at' => '2017-08-16 01:25:09',
                ),
        ));
        
        
    }
}