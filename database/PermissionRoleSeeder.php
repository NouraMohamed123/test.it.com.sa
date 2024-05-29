<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     // Insert some stuff
	// DB::table('role_has_permissions')->insert(
	// 	array(
	// 		[
	// 			'permission_id' => 1,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 2,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 3,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 4,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 5,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 6,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 7,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 8,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 9,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 10,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 11,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 12,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 13,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 14,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 15,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 16,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 17,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 18,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 19,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 20,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 21,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 22,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 23,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 24,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 25,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 26,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 27,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 28,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 29,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 30,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 31,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 32,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 33,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 34,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 35,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 36,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 37,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 38,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 39,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 40,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 41,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 42,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 43,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 44,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 45,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 46,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 47,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 48,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 49,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 50,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 51,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 52,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 53,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 54,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 55,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 56,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 57,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 58,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 59,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 60,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 61,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 62,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 63,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 64,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 65,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 66,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 67,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 68,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 69,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 70,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 71,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 72,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 73,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 74,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 75,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 76,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 77,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 78,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 79,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 80,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 81,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 82,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 83,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 84,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 85,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 86,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 87,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 88,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 89,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 90,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 91,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 92,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 93,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 94,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 95,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 96,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 97,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 98,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 99,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 100,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 101,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 102,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 103,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 104,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 105,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 106,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 107,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 108,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 109,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 110,
	// 			'role_id'       => 1,
	// 		],
	// 		[
	// 			'permission_id' => 111,
	// 			'role_id'       => 1,
	// 		],
	// 		//employee access
	// 		[
	// 			'permission_id' => 21,
	// 			'role_id'       => 2,
	// 		],
	// 		[
	// 			'permission_id' => 25,
	// 			'role_id'       => 2,
	// 		],
	// 		[
	// 			'permission_id' => 55,
	// 			'role_id'       => 2,
	// 		],

	// 	)
	// );
    // }
    public function run()
    {
        $permissions = [
            ['name' => 'employee-view', 'guard_name' => 'api'],
            ['name' => 'employee-add', 'guard_name' => 'api'],
            ['name' => 'employee-edit', 'guard_name' => 'api'],
            ['name' => 'employee-delete', 'guard_name' => 'api'],
            ['name' => 'jobs-view', 'guard_name' => 'api'],
            ['name' => 'jobs-add', 'guard_name' => 'api'],
            ['name' => 'jobs-edit', 'guard_name' => 'api'],
            ['name' => 'jobs-delete', 'guard_name' => 'api'],
            ['name' => 'user-view', 'guard_name' => 'api'],
            ['name' => 'user-add', 'guard_name' => 'api'],
            ['name' => 'user-edit', 'guard_name' => 'api'],
            ['name' => 'user-delete', 'guard_name' => 'api'],
            ['name' => 'company-view', 'guard_name' => 'api'],
            ['name' => 'company-add', 'guard_name' => 'api'],
            ['name' => 'company-edit', 'guard_name' => 'api'],
            ['name' => 'company-delete', 'guard_name' => 'api'],
            ['name' => 'department-view', 'guard_name' => 'api'],
            ['name' => 'department-add', 'guard_name' => 'api'],
            ['name' => 'department-edit', 'guard_name' => 'api'],
            ['name' => 'department-delete', 'guard_name' => 'api'],
            ['name' => 'designation-view', 'guard_name' => 'api'],
            ['name' => 'designation-add', 'guard_name' => 'api'],
            ['name' => 'designation-edit', 'guard_name' => 'api'],
            ['name' => 'designation-delete', 'guard_name' => 'api'],
            ['name' => 'policy-view', 'guard_name' => 'api'],
            ['name' => 'policy-add', 'guard_name' => 'api'],
            ['name' => 'policy-edit', 'guard_name' => 'api'],
            ['name' => 'policy-delete', 'guard_name' => 'api'],
            ['name' => 'announcement-view', 'guard_name' => 'api'],
            ['name' => 'announcement-add', 'guard_name' => 'api'],
            ['name' => 'announcement-edit', 'guard_name' => 'api'],
            ['name' => 'announcement-delete', 'guard_name' => 'api'],
            ['name' => 'office_shift-view', 'guard_name' => 'api'],
            ['name' => 'office_shift-add', 'guard_name' => 'api'],
            ['name' => 'office_shift-edit', 'guard_name' => 'api'],
            ['name' => 'office_shift-delete', 'guard_name' => 'api'],
            ['name' => 'event-view', 'guard_name' => 'api'],
            ['name' => 'event-add', 'guard_name' => 'api'],
            ['name' => 'event-edit', 'guard_name' => 'api'],
            ['name' => 'event-delete', 'guard_name' => 'api'],
            ['name' => 'holiday-view', 'guard_name' => 'api'],
            ['name' => 'holiday-add', 'guard_name' => 'api'],
            ['name' => 'holiday-edit', 'guard_name' => 'api'],
            ['name' => 'holiday-delete', 'guard_name' => 'api'],
            ['name' => 'award-view', 'guard_name' => 'api'],
            ['name' => 'award-add', 'guard_name' => 'api'],
            ['name' => 'award-edit', 'guard_name' => 'api'],
            ['name' => 'award-delete', 'guard_name' => 'api'],
            ['name' => 'award-type', 'guard_name' => 'api'],
            ['name' => 'complaint-view', 'guard_name' => 'api'],
            ['name' => 'complaint-add', 'guard_name' => 'api'],
            ['name' => 'complaint-edit', 'guard_name' => 'api'],
            ['name' => 'complaint-delete', 'guard_name' => 'api'],
            ['name' => 'travel-view', 'guard_name' => 'api'],
            ['name' => 'travel-add', 'guard_name' => 'api'],
            ['name' => 'travel-edit', 'guard_name' => 'api'],
            ['name' => 'travel-delete', 'guard_name' => 'api'],
            ['name' => 'arrangement-type', 'guard_name' => 'api'],
            ['name' => 'attendance-view', 'guard_name' => 'api'],
            ['name' => 'attendance-add', 'guard_name' => 'api'],
            ['name' => 'attendance-edit', 'guard_name' => 'api'],
            ['name' => 'attendance-delete', 'guard_name' => 'api'],
            ['name' => 'account-view', 'guard_name' => 'api'],
            ['name' => 'account-add', 'guard_name' => 'api'],
            ['name' => 'account-edit', 'guard_name' => 'api'],
            ['name' => 'account-delete', 'guard_name' => 'api'],
            ['name' => 'deposit-view', 'guard_name' => 'api'],
            ['name' => 'deposit-add', 'guard_name' => 'api'],
            ['name' => 'deposit-edit', 'guard_name' => 'api'],
            ['name' => 'deposit-delete', 'guard_name' => 'api'],
            ['name' => 'expense-view', 'guard_name' => 'api'],
            ['name' => 'expense-add', 'guard_name' => 'api'],
            ['name' => 'expense-edit', 'guard_name' => 'api'],
            ['name' => 'expense-delete', 'guard_name' => 'api'],
            ['name' => 'client-view', 'guard_name' => 'api'],
            ['name' => 'client-add', 'guard_name' => 'api'],
            ['name' => 'client-edit', 'guard_name' => 'api'],
            ['name' => 'client-delete', 'guard_name' => 'api'],
            ['name' => 'deposit-category', 'guard_name' => 'api'],
            ['name' => 'payment-method', 'guard_name' => 'api'],
            ['name' => 'expense-category', 'guard_name' => 'api'],
            ['name' => 'project-view', 'guard_name' => 'api'],
            ['name' => 'project-status', 'guard_name' => 'api'],
            ['name' => 'project-add', 'guard_name' => 'api'],
            ['name' => 'project-edit', 'guard_name' => 'api'],
            ['name' => 'project-delete', 'guard_name' => 'api'],
            ['name' => 'task-view', 'guard_name' => 'api'],
            ['name' => 'task-status', 'guard_name' => 'api'],
            ['name' => 'task-add', 'guard_name' => 'api'],
            ['name' => 'task-edit', 'guard_name' => 'api'],
            ['name' => 'task-delete', 'guard_name' => 'api'],
            ['name' => 'leave-view', 'guard_name' => 'api'],
            ['name' => 'leave-add', 'guard_name' => 'api'],
            ['name' => 'leave-edit', 'guard_name' => 'api'],
            ['name' => 'leave-delete', 'guard_name' => 'api'],
            ['name' => 'training-view', 'guard_name' => 'api'],
            ['name' => 'training-add', 'guard_name' => 'api'],
            ['name' => 'training-edit', 'guard_name' => 'api'],
            ['name' => 'training-delete', 'guard_name' => 'api'],
            ['name' => 'trainer', 'guard_name' => 'api'],
            ['name' => 'training-skills', 'guard_name' => 'api'],
            ['name' => 'settings', 'guard_name' => 'api'],
            ['name' => 'currency', 'guard_name' => 'api'],
            ['name' => 'backup', 'guard_name' => 'api'],
            ['name' => 'group-permission', 'guard_name' => 'api'],
            ['name' => 'attendance-report', 'guard_name' => 'api'],
            ['name' => 'employee-report', 'guard_name' => 'api'],
            ['name' => 'project-report', 'guard_name' => 'api'],
            ['name' => 'task-report', 'guard_name' => 'api'],
            ['name' => 'expense-report', 'guard_name' => 'api'],
            ['name' => 'deposit-report', 'guard_name' => 'api'],
            ['name' => 'employee-details', 'guard_name' => 'api'],
            ['name' => 'leave-type', 'guard_name' => 'api'],
            ['name' => 'project-details', 'guard_name' => 'api'],
            ['name' => 'task-details', 'guard_name' => 'api'],
            ['name' => 'client-details', 'guard_name' => 'api'],
            ['name' => 'invoice-view', 'guard_name' => 'api'],
            ['name' => 'invoice-add', 'guard_name' => 'api'],
            ['name' => 'invoice-edit', 'guard_name' => 'api'],
            ['name' => 'invoice-delete', 'guard_name' => 'api'],
            ['name' => 'payslip-view', 'guard_name' => 'api'],
            ['name' => 'payslip-add', 'guard_name' => 'api'],
            ['name' => 'payslip-edit', 'guard_name' => 'api'],
            ['name' => 'payslip-delete', 'guard_name' => 'api'],
            ['name' => 'role-view', 'guard_name' => 'api'],
            ['name' => 'role-add', 'guard_name' => 'api'],
            ['name' => 'role-edit', 'guard_name' => 'api'],
            ['name' => 'role-delete', 'guard_name' => 'api'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
