<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/edit_emp/{id}',[EmployeeController::class,'editEmp'])->name('edit-employee');
Route::get('/del_emp/{id}',[EmployeeController::class,'delEmp'])->name('del-employee');
Route::get('/add-employee',[EmployeeController::class,'addEmp'])->name('add-employee');
Route::get('/',[EmployeeController::class,'index'])->name('list-employee');

