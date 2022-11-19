<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoRolePermissionTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_permission')->delete();

        // check if table is empty
        if (DB::table('role_permission')->count() == 0) {
            $roles = DB::table('roles')->get();
            $permissions = DB::table('permissions')->get();
            foreach ($roles as $role) {
                foreach ($permissions as $permission) {
                    DB::table('role_permission')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id,
                    ]);
                }
            }
        }
    }
}
