<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\DepartmentsController;
use App\Http\Controllers\Api\ProjectController;

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
    /////////////jobs
    Route::get('jobs',[JobsController::class, 'index'] );
    Route::post('jobs',[JobsController::class, 'store'] );
    Route::post('/jobs/{jop}', [JobsController::class, 'update']);
    Route::delete('/jobs/{jop}', [JobsController::class, 'destroy']);
    Route::post('jobs/delete/by_selection', [JobsController::class, 'delete_by_selection']);
    ////////companies
    Route::get('companies',[CompanyController::class, 'index'] );
    Route::post('companies',[CompanyController::class, 'store'] );
    Route::post('/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);
    Route::post('companies/delete/by_selection', [CompanyController::class, 'delete_by_selection']);
    ////////////////candidates
    Route::get('candidates',[CandidateController::class, 'index'] );
    Route::post('candidates',[CandidateController::class, 'store'] );
    Route::post('/candidates/{candidate}', [CandidateController::class, 'update']);
    Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy']);
    Route::post('candidates/delete/by_selection', [CandidateController::class, 'delete_by_selection']);
     ////////////////project
     Route::get('projects',[ProjectController::class, 'index'] );
     Route::get('projects/{id}',[ProjectController::class, 'show'] );
     Route::post('projects',[ProjectController::class, 'store'] );
     Route::post('projects/{id}',[ProjectController::class, 'update'] );
     Route::post('projects/delete/by_selection', [ProjectController::class, 'delete_by_selection']);
     Route::post('create/project/discussions',[ProjectController::class, 'Create_project_discussions'] );
     Route::post('destroy/project/discussions/{id}',[ProjectController::class, 'destroy_project_discussion'] );
     Route::post('create/project/issues',[ProjectController::class, 'Create_project_issues'] );
     Route::post('update/project/issues/{id}',[ProjectController::class, 'update_project_issues'] );
     Route::post('delete/project/issues/{id}',[ProjectController::class, 'destroy_project_issues'] );
     Route::post('create/project/documents',[ProjectController::class, 'Create_project_documents'] );
     Route::post('delete/project/documents/{id}',[ProjectController::class, 'destroy_project_documents'] );
     //departments
     Route::get('departments',[DepartmentsController::class, 'index'] );
     Route::post('departments',[DepartmentsController::class, 'store'] );
     Route::post('departments/{id}',[DepartmentsController::class, 'update'] );
     Route::post('/delete/departments/{id}',[DepartmentsController::class, 'destroy'] );
     Route::post('departments/delete/by_selection', [ProjectController::class, 'delete_by_selection']);
     Route::get('departments/company',[DepartmentsController::class, 'Get_departments_by_company'] );
     Route::get('departments/all',[DepartmentsController::class, 'Get_all_Departments'] );


    Route::get('/user',function (Request $request) {
        return $request->user();
    });
});


