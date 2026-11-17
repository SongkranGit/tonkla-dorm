<?php

use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ApprovalPrivilegeSeeder extends Seeder
{
    private $table = "approval_privileges";
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->table)->truncate();

        $approval_privileges = [
            [
                'user_id'          => 1,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 2,
                'can_approve'      => 1,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 3,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 4,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 5,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 6,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 7,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 8,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 9,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 10,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
            [
                'user_id'          => 11,
                'can_approve'      => 0,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ],
        ];

        DB::table($this->table)->insert($approval_privileges);
    }
}
