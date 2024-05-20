<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\EmployeesController;

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

], function ($router) {
    //------------------------------- Jobs -----------------------\\
    //----------------------------------------------------------------\\

    Route::get('jobs', [JobsController::class, 'index']);
    Route::post('jobs', [JobsController::class, 'store']);
    Route::post('/jobs/{jop}', [JobsController::class, 'update']);
    Route::delete('/jobs/{jop}', [JobsController::class, 'destroy']);
    Route::post('jobs/delete/by_selection', [JobsController::class, 'delete_by_selection']);
     //------------------------------- Companies -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('companies', [CompanyController::class, 'index']);
    Route::post('companies', [CompanyController::class, 'store']);
    Route::post('/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);
    Route::post('companies/delete/by_selection', [CompanyController::class, 'delete_by_selection']);
      //------------------------------- candidates -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('candidates', [CandidateController::class, 'index']);
    Route::post('candidates', [CandidateController::class, 'store']);
    Route::post('/candidates/{candidate}', [CandidateController::class, 'update']);
    Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy']);
    Route::post('candidates/delete/by_selection', [CandidateController::class, 'delete_by_selection']);
    //------------------------------- Task -----------------------\\
    //----------------------------------------------------------------\\
    Route::get('tasks', [TasksController::class, 'index']);
    Route::post('tasks', [TasksController::class, 'store']);
    Route::post('/tasks/{task}', [TasksController::class, 'update']);
    Route::delete('/tasks/{task}', [TasksController::class, 'destroy']);
    Route::post("tasks/delete/by_selection", [TasksController::class, 'delete_by_selection']);
    Route::post("update_task_status/{id}", [TasksController::class, 'update_task_status']);
    Route::get("tasks_kanban", [TasksController::class, 'tasks_kanban']);
    Route::post("task_discussions", [TasksController::class, 'Create_task_discussions']);
    Route::delete("task_discussions/{id}", [TasksController::class, 'destroy_task_discussion']);
    Route::post("task_documents", [TasksController::class, 'Create_task_documents']);
    Route::delete("task_documents/{id}", [TasksController::class, 'destroy_task_documents']);
    //------------------------------- users --------------------------\\
    //----------------------------------------------------------------\\
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::post('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}',  [UserController::class, 'destroy']);
    Route::post('assignRole', [UserController::class, 'assignRole']);
       //------------------------------- Employee --------------------------\\
     //--------------------------------------------------------------------\\
     Route::get('employees', [EmployeeController::class, 'index']);
    Route::post('employees', [EmployeeController::class, 'store']);
    Route::post('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}',  [EmployeeController::class, 'destroy']);
    // Route::get("Get_all_employees",  [EmployeeController::class, 'Get_all_employees']);
    Route::get("Get_employees_by_company", [EmployeeController::class, 'Get_employees_by_company'] );
    Route::get("Get_employees_by_department", [EmployeeController::class, 'Get_employees_by_department'] );
    Route::get("Get_office_shift_by_company", [EmployeeController::class, 'Get_office_shift_by_company'] );
    Route::put("update_social_profile/{id}",  [EmployeeController::class, 'update_social_profile']);
    Route::post("employees/delete/by_selection", [EmployeeController::class, 'delete_by_selection'] );

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
