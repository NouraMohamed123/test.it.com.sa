<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

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
