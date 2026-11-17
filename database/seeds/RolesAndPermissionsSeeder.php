<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Database\TruncateTable;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
	{

        DB::table('roles')->delete();


    	// Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);
        // Permission::create(['name' => 'publish articles']);
        // Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $role = Role::create(['name' => 'admin']);
        // $role->givePermissionTo('edit articles');
        // $role->givePermissionTo('delete articles');

        $role = Role::create(['name' => 'user']);
        // $role->givePermissionTo('publish articles');
        // $role->givePermissionTo('unpublish articles');

        $role = Role::create(['name' => 'owner']);
    }
}