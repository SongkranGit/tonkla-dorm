<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(KeycardsTableSeeder::class);
        $this->call(RoomStayHistoriesTableSeeder::class);
        $this->call(RoomUtilityHistoriesTableSeeder::class);
        $this->call(ActivityLogTableSeeder::class);
        $this->call(EquipmentsTableSeeder::class);
        $this->call(RoomEquipmentTableSeeder::class);
        $this->call(ApprovalPrivilegeSeeder::class);
    }
}
