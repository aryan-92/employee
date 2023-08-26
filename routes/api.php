<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/store-employee',[EmployeeController::class,'Store'])->name('store-employee');
Route::get('/list-employee',[EmployeeController::class,'empList']);
Route::get('/single-employee/{id}',[EmployeeController::class,'employee']);
Route::post('/employee-edit',[EmployeeController::class,'empEdit']);
Route::get('/delete_emp',[EmployeeController::class,'empDelete']);

