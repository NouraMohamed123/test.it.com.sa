<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert some stuff
        $superRole = Role::create([
            'name' => 'Super2',
            'description' => 'Admin1',
            'guard_name' => 'api',
        ]);

        $permissions = Permission::where('guard_name', 'api')->pluck('id')->toArray();
           $superRole->syncPermissions($permissions);
    }
}
