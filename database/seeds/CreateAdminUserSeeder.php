<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     DB::table('users')->insert(
    //         array(

    //             'username' => 'admin',
    //             'email' => 'admin@example.com',
    //             'password' => '$2y$10$IFj6SwqC0Sxrsiv4YkCt.OJv1UV4mZrWuyLoRG7qt47mseP9mJ58u',

    //             'role_users_id' =>7,
    //             'status' => 1,
    //         )
    //     );
    //     $user = User::findOrFail(7);
    //     $user->assignRole(5);
    // }
    public function run(): void
    {
        // Insert admin user into the database
        DB::table('users')->insert([
            'username' => 'admin1',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345689'), // Consider hashing passwords securely
            'role_users_id' => 10,
            'status' => 1,
        ]);

        // Find the user by the inserted ID (assumed to be 7 here)
        $user = User::where('email', 'admin@admin.com')->firstOrFail();


        // Ensure the role exists before assigning it
        $role = Role::firstOrCreate(['name' => 'Super2']);

        // Assign the role to the user
        $user->assignRole($role->name);
    }

}
