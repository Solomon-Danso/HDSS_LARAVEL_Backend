<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("Setup",[SetupController::class,"Setup"]);
Route::post("LocalSetup",[SetupController::class,"LocalSetup"]);
Route::post("CompanyToken",[SetupController::class,"CompanyToken"]);
Route::post("LocalCompanyToken",[SetupController::class,"LocalCompanyToken"]);
Route::post("RegisterStudent",[StudentController::class,"RegisterStudent"]);
Route::post("UpdateStudent",[StudentController::class,"UpdateStudent"]);

Route::post("BulkRegisterStudent",[StudentController::class,"BulkRegisterStudent"]);
Route::post("BulkAutoGeneratedRegisterStudent",[StudentController::class,"BulkAutoGeneratedRegisterStudent"]);
Route::post("BulkAutoGeneratedAuthenticators",[StudentController::class,"BulkAutoGeneratedAuthenticators"]);


Route::get("GetStudent/{StudentId}/{CompanyId}",[StudentController::class,"GetStudent"]);
Route::post("GetStudentInAClass",[StudentController::class,"GetStudentInAClass"]);
Route::post("GetStudentInAClassFile",[StudentController::class,"GetStudentInAClassFile"]);

Route::post("GetStudentInASchool",[StudentController::class,"GetStudentInASchool"]);
Route::post("GetStudentInSchoolFile",[StudentController::class,"GetStudentInSchoolFile"]);
Route::post("GetStudentInSchoolAuthFile",[StudentController::class,"GetStudentInSchoolAuthFile"]);


Route::delete("DeleteStudent/{StudentId}/{CompanyId}",[StudentController::class,"DeleteStudent"]);






