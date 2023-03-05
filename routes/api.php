<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix("admin")->group(function () {
    Route::post("/login", [AuthController::class, 'login']);
    Route::get("/employee/mangersOption", [EmployeeController::class, 'mangersOption']);
    Route::get("/employee/employeesOption", [EmployeeController::class, 'employeesOption']);
    Route::get("/department/departmentsOption", [DepartmentController::class, 'departmentsOption']);

    Route::middleware(['jwt.auth:admin'])->group(function () {
        Route::get("/logout", [AuthController::class, 'logout']);

            Route::post("/employee/create", [EmployeeController::class, 'create']);
            Route::put("/employee/update/{id}", [EmployeeController::class, 'update']);
            Route::delete("/employee/delete/{id}", [EmployeeController::class, 'delete']);
            Route::post("/employee/search", [EmployeeController::class, 'search']);

            Route::post("/department/create", [DepartmentController::class, 'create']);
            Route::put("/department/update/{id}", [DepartmentController::class, 'update']);
            Route::delete("/department/delete/{id}", [DepartmentController::class, 'delete']);
            Route::post("/department/search", [DepartmentController::class, 'search']);

            Route::post("/task/search", [TaskController::class, 'search']);

    });

});



Route::prefix("employee")->group(function () {
    Route::post("/login", [EmployeeController::class, 'login']);

    Route::middleware(['jwt.auth:employee'])->group(function () {

             Route::get("/tasks", [EmployeeController::class, 'tasks']);
             Route::post("/manger_employees", [EmployeeController::class, 'employees']);

             Route::post("/task/create", [TaskController::class, 'create']);
             Route::put("/task/update/{id}", [TaskController::class, 'update']);
             Route::get("/task/get_tasks_manger", [TaskController::class, 'get_tasks_manger']);
             Route::get("/logout", [EmployeeController::class, 'logout']);

    });

});
