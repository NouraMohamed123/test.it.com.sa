<?php

use App\Http\Controllers\Api\ArrangementTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\PoliciesController;
use App\Http\Controllers\Api\TrainersController;
use App\Http\Controllers\Api\TrainingController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\LeaveTypeController;
use App\Http\Controllers\Api\AttendancesController;
use App\Http\Controllers\Api\AwardController;
use App\Http\Controllers\Api\AwardTypeController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\DepartmentsController;
use App\Http\Controllers\Api\DesignationsController;
use App\Http\Controllers\Api\OfficeShiftController;
use App\Http\Controllers\Api\TrainingSkillsController;
use App\Http\Controllers\Api\EmployeeSessionController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TravelController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([
    'prefix' => 'auth'

], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group([
    'middleware' => 'auth:api',
    // 'prefix' => 'dashboard'

], function ($router) {
    //roles
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles/{id}', [RoleController::class, 'show']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::post('/roles/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    //------------------------------- Jobs -----------------------\\
    //----------------------------------------------------------------\\

    Route::get('jobs', [JobsController::class, 'index']);
    Route::get('jobs/{id}', [JobsController::class, 'show']);
    Route::post('jobs', [JobsController::class, 'store']);
    Route::post('/jobs/{id}', [JobsController::class, 'update']);
    Route::delete('/jobs/{id}', [JobsController::class, 'destroy']);
    Route::post('jobs/delete/by_selection', [JobsController::class, 'delete_by_selection']);
    //------------------------------- Companies -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('companies', [CompanyController::class, 'index']);
    Route::get('companies/{id}', [CompanyController::class, 'show']);
    Route::post('companies', [CompanyController::class, 'store']);
    Route::post('/companies/{id}', [CompanyController::class, 'update']);
    Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);
    Route::post('companies/delete/by_selection', [CompanyController::class, 'delete_by_selection']);
    Route::post('quick-entry-company/{id}', [CompanyController::class, 'QuickEntry']);
    Route::get('verification-attendance', [CompanyController::class, 'verification_attendance']);
    Route::get('verification-leave', [CompanyController::class, 'verification_leave']);
    Route::post('import-companies', [CompanyController::class, 'import']);
    Route::post('company-user', [CompanyController::class, 'store_admin']);
    //------------------------------- candidates -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('candidates', [CandidateController::class, 'index']);
    Route::get('/candidates/{id}', [CandidateController::class, 'show']);
    Route::post('candidates', [CandidateController::class, 'store']);
    Route::post('/candidates/{id}', [CandidateController::class, 'update']);
    Route::delete('/candidates/{id}', [CandidateController::class, 'destroy']);
    Route::post('candidates/delete/by_selection', [CandidateController::class, 'delete_by_selection']);
    ////////////////project
    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{id}', [ProjectController::class, 'show']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::post('projects/delete/{id}', [ProjectController::class, 'destroy']);
    Route::post('projects/{id}', [ProjectController::class, 'update']);
    Route::post('projects/delete/by_selection', [ProjectController::class, 'delete_by_selection']);
    Route::post('create/project/discussions', [ProjectController::class, 'Create_project_discussions']);
    Route::post('destroy/project/discussions/{id}', [ProjectController::class, 'destroy_project_discussion']);
    Route::post('create/project/issues', [ProjectController::class, 'Create_project_issues']);
    Route::post('update/project/issues/{id}', [ProjectController::class, 'update_project_issues']);
    Route::post('delete/project/issues/{id}', [ProjectController::class, 'destroy_project_issues']);
    Route::post('create/project/documents', [ProjectController::class, 'Create_project_documents']);
    Route::post('delete/project/documents/{id}', [ProjectController::class, 'destroy_project_documents']);
    //departments
    Route::get('departments', [DepartmentsController::class, 'index']);
    Route::get('departments/{id}', [DepartmentsController::class, 'show']);
    Route::post('departments', [DepartmentsController::class, 'store']);
    Route::post('departments/{id}', [DepartmentsController::class, 'update']);
    Route::post('/delete/departments/{id}', [DepartmentsController::class, 'destroy']);
    Route::post('departments/delete/by_selection', [DepartmentsController::class, 'delete_by_selection']);
    Route::get('departments/company', [DepartmentsController::class, 'Get_departments_by_company']);
    Route::get('departments/all', [DepartmentsController::class, 'Get_all_Departments']);

    //------------------------------- Task -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('tasks', [TasksController::class, 'index']);
    Route::get('tasks/{id}', [TasksController::class, 'show']);
    Route::post('tasks', [TasksController::class, 'store']);
    Route::post('/tasks/{id}', [TasksController::class, 'update']);
    Route::delete('/tasks/{id}', [TasksController::class, 'destroy']);
    Route::post("tasks/delete/by_selection", [TasksController::class, 'delete_by_selection']);
    Route::post("update_task_status/{id}", [TasksController::class, 'update_task_status']);
    Route::get("tasks_kanban", [TasksController::class, 'tasks_kanban']);
    Route::post("task_discussions", [TasksController::class, 'Create_task_discussions']);
    Route::delete("task_discussions/{id}", [TasksController::class, 'destroy_task_discussion']);
    Route::post("task_documents", [TasksController::class, 'Create_task_documents']);
    Route::delete("task_documents/{id}", [TasksController::class, 'destroy_task_documents']);
    //repeating
     Route::post('repeating-task', [TasksController::class, 'repeating']);
    //------------------------------- users --------------------------\\
    //----------------------------------------------------------------\\
    Route::get('/me', [UserController::class, 'me']);
    Route::get('/show-privcy', [UserController::class, 'showPrivcy']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}',  [UserController::class, 'destroy']);
    Route::post('assignRole', [UserController::class, 'assignRole']);
    Route::post('import-user', [UserController::class, 'import']);

    //------------------------------- Employee --------------------------\\
    //--------------------------------------------------------------------\\
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::get('employees/{id}', [EmployeeController::class, 'show']);
    Route::post('employees', [EmployeeController::class, 'store']);
    Route::post('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}',  [EmployeeController::class, 'destroy']);
    Route::get("Get_employees_by_company", [EmployeeController::class, 'Get_employees_by_company']);
    Route::get("Get_employees_by_department", [EmployeeController::class, 'Get_employees_by_department']);
    Route::get("Get_office_shift_by_company", [EmployeeController::class, 'Get_office_shift_by_company']);
    // Route::put("update_social_profile/{id}",  [EmployeeController::class, 'update_social_profile']);
    Route::post("employees/delete/by_selection", [EmployeeController::class, 'delete_by_selection']);
    Route::post('import-employees', [EmployeeController::class, 'import']);
    //------------------------------- Attendances ------------------------\\
    //--------------------------------------------------------------------\\

    Route::get('attendances', [AttendancesController::class, 'index']);
    Route::get('attendances/{id}', [AttendancesController::class, 'show']);
    Route::post('attendances', [AttendancesController::class, 'store']);
    Route::post('/attendances/{id}', [AttendancesController::class, 'update']);
    Route::delete('/attendances/{id}',  [AttendancesController::class, 'destroy']);
    Route::get("daily_attendance",  [AttendancesController::class, 'daily_attendance']);
    Route::post('attendance_by_employee/{id}', [EmployeeSessionController::class, 'attendance_by_employee']); ///not work
    Route::post("attendances/delete/by_selection",  [AttendancesController::class, 'delete_by_selection']);
    //------------------------------- training ----------------------\\
    //----------------------------------------------------------------\\

    Route::get('trainings', [TrainingController::class, 'index']);
    Route::get('trainings/{id}', [TrainingController::class, 'show']);
    Route::post('trainings', [TrainingController::class, 'store']);
    Route::post('/trainings/{id}', [TrainingController::class, 'update']);
    Route::delete('/trainings/{id}',  [TrainingController::class, 'destroy']);
    Route::post("trainings/delete/by_selection", "TrainingController@delete_by_selection");

    //------------------------------- trainers ----------------------\\
    Route::get('trainers', [TrainersController::class, 'index']);
    Route::get('trainers/{id}', [TrainersController::class, 'show']);
    Route::post('trainers', [TrainersController::class, 'store']);
    Route::post('/trainers/{id}', [TrainersController::class, 'update']);
    Route::delete('/trainers/{id}',  [TrainersController::class, 'destroy']);
    Route::post("trainers/delete/by_selection", "TrainersController@delete_by_selection");
    //------------------------------- training_skills ----------------------\\
    Route::get('training_skills', [TrainingSkillsController::class, 'index']);
    Route::get('training_skills/{id}', [TrainingSkillsController::class, 'show']);
    Route::post('training_skills', [TrainingSkillsController::class, 'store']);
    Route::post('/training_skills/{id}', [TrainingSkillsController::class, 'update']);
    Route::delete('/training_skills/{id}',  [TrainingSkillsController::class, 'destroy']);
    Route::post("training_skills/delete/by_selection", "TrainingSkillsController@delete_by_selection");
    //------------------------------- Request leave  -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('leave', [LeaveController::class, 'index']);
    Route::get('leave/{id}', [LeaveController::class, 'show']);
    Route::post('leave', [LeaveController::class, 'store']);
    Route::post('/leave/{id}', [LeaveController::class, 'update']);
    Route::delete('/leave/{id}',  [LeaveController::class, 'destroy']);
    Route::post("leave/delete/by_selection", [LeaveController::class, 'delete_by_selection']);
    Route::get('leave_type', [LeaveTypeController::class, 'index']);
    Route::get('leave_type/{id}', [LeaveTypeController::class, 'show']);
    Route::post('leave_type', [LeaveTypeController::class, 'store']);
    Route::post('/leave_type/{leave_type}', [LeaveTypeController::class, 'update']);
    Route::delete('/leave_type/{leave_type}',  [LeaveTypeController::class, 'destroy']);
    Route::post("leave_type/delete/by_selection", [LeaveTypeController::class, 'delete_by_selection']);
    //policies
    Route::get('/policies', [PoliciesController::class, 'index']);
    Route::get('/policies/{id}', [PoliciesController::class, 'show']);
    Route::post('/policies', [PoliciesController::class, 'store']);
    Route::put('/policies/{id}', [PoliciesController::class, 'update']);
    Route::delete('/policies/{id}', [PoliciesController::class, 'destroy']);
    Route::post('/policies/delete_by_selection', [PoliciesController::class, 'delete_by_selection']);
    //office_shifts
    Route::get('/office_shifts', [OfficeShiftController::class, 'index']);
    Route::get('/office_shifts/{id}', [OfficeShiftController::class, 'show']);
    Route::post('/office_shifts', [OfficeShiftController::class, 'store']);
    Route::post('/office_shifts/{id}', [OfficeShiftController::class, 'update']);
    Route::delete('/office_shifts/{id}', [OfficeShiftController::class, 'destroy']);

    //event
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::post('/events', [EventController::class, 'store']);
    Route::post('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::post('/events/delete/selection', [EventController::class, 'delete_by_selection']);
    // Route::post('office_shifts/delete_by_selection', [OfficeShiftController::class, 'delete_by_selection']);
    Route::post("office_shifts/delete/by_selection", [OfficeShiftController::class, 'delete_by_selection']);
    // Routes for HolidayController
    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::post('/holidays', [HolidayController::class, 'store']);
    Route::get('/holidays/{id}', [HolidayController::class, 'show']);
    Route::post('/holidays/{id}', [HolidayController::class, 'update']);
    Route::delete('/holidays/{id}', [HolidayController::class, 'destroy']);
    Route::post('/holidays/delete/selection', [HolidayController::class, 'delete_by_selection']);
    // Award routes
    Route::get('/awards', [AwardController::class, 'index']);
    Route::post('/awards', [AwardController::class, 'store']);
    Route::get('/awards/{id}', [AwardController::class, 'show']);
    Route::post('/awards/{id}', [AwardController::class, 'update']);
    Route::delete('/awards/{id}', [AwardController::class, 'destroy']);
    Route::post('/awards/delete/selection', [AwardController::class, 'delete_by_selection']);
    // AwardType routes
    Route::get('/award-types', [AwardTypeController::class, 'index']);
    Route::post('/award-types', [AwardTypeController::class, 'store']);
    Route::get('/award-types/{id}', [AwardTypeController::class, 'show']);
    Route::post('/award-types/{id}', [AwardTypeController::class, 'update']);
    Route::delete('/award-types/{id}', [AwardTypeController::class, 'destroy']);
    Route::post('/award-types/delete/selection', [AwardTypeController::class, 'delete_by_selection']);
    // Complaint routes
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::get('/complaints/{id}', [ComplaintController::class, 'show']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
    Route::post('/complaints/{id}', [ComplaintController::class, 'update']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);
    Route::post('/complaints/delete/selection', [ComplaintController::class, 'delete_by_selection']);
    // Travel routes
    Route::get('/travels', [TravelController::class, 'index']);
    Route::get('/travels/{id}', [TravelController::class, 'show']);
    Route::post('/travels', [TravelController::class, 'store']);
    Route::put('/travels/{id}', [TravelController::class, 'update']);
    Route::delete('/travels/{id}', [TravelController::class, 'destroy']);
    Route::post('/travels/delete/selection', [TravelController::class, 'delete_by_selection']);

    //report
    Route::get('attendance-report', [ReportController::class, 'attendance_report_index']);
    Route::get('employee-report', [ReportController::class, 'employee_report_index']);
    Route::get('leave-report', [ReportController::class, 'leave_report_index']);
    Route::get('project-report', [ReportController::class, 'project_report_index']);
    Route::get('task-report', [ReportController::class, 'task_report_index']);
    Route::get('expense-report', [ReportController::class, 'expense_report_index']);
    Route::get('deposit-report', [ReportController::class, 'deposit_report_index']);
    Route::post('fetch-department', [ReportController::class, 'fetchDepartment']);
    Route::post('fetch-designation', [ReportController::class, 'fetchDesignation']);

    //setting
    Route::get('settings', [SettingController::class, 'index']);

    Route::post('settings/{id}', [SettingController::class, 'update']);

    //designation
    Route::get('designations', [DesignationsController::class, 'index']);
    Route::get('designations/{id}', [DesignationsController::class, 'show']);
    Route::post('designations', [DesignationsController::class, 'store']);
    Route::post('designations/{id}', [DesignationsController::class, 'update']);
    Route::delete('designations/{id}', [DesignationsController::class, 'destroy']);
    Route::post('designations/delete/selection', [DesignationsController::class, 'delete_by_selection']);
    Route::get('designations/department', [DesignationsController::class, 'get_designations_by_department']);

    //arrangement-types
    Route::get('arrangement/types', [ArrangementTypeController::class, 'index']);
    Route::post('arrangement/types', [ArrangementTypeController::class, 'store']);
    Route::get('arrangement/types/{id}', [ArrangementTypeController::class, 'show']);
    Route::post('arrangement/types/{id}', [ArrangementTypeController::class, 'update']);
    Route::delete('arrangement/types/{id}', [ArrangementTypeController::class, 'destroy']);
    Route::post('delete/arrangement/selection', [ArrangementTypeController::class, 'delete_by_selection']);


});
