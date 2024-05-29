<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

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
	    DB::table('roles')->insert(
            array(
                [

                    'name'  => 'Super',
                    'description' => 'Super Admin',
                    'guard_name' => 'api',
                ],

            )
        );
    }
}
