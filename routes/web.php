<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LanguageController;

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

Route::controller(SpaceController::class)->group(function () {
    Route::get('/spaces', 'index');
    Route::get('/space/{id}', 'show');
    Route::post('/space/save', 'store');
    Route::put('/space/update', 'update');
    Route::delete('/space/delete', 'destroy');
});


Route::controller(DepartmentController::class)->group(function () {
    Route::get('/departments', 'index');
    Route::get('/department/{id}', 'show');
    Route::post('/department/save', 'store');
    Route::put('/department/update', 'update');
    Route::delete('/department/delete', 'destroy');
    Route::post('/department/type', 'getByDepartmentByType');
    Route::put('/department/update/type', 'updateDepartmentType');
});

Route::controller(IncidenceController::class)->group(function () {
    Route::get('/incidences', 'index');
    Route::get('/incidence/{id}', 'show');
    Route::put('/incidence/update', 'update'); 
    Route::post('/incidences/state', 'showIncidencesByState');
    Route::post('/incidence/state/change', 'changeState');
    Route::post('/incidence/change/tech', 'changeTech');
    Route::post('/incidences/filter', 'filterIncidences');
});


Route::controller(UserController::class)->group(function () {
    Route::get('/users','index');
    Route::get('/user/{id}', 'show');
    Route::post('/user/save', 'store');
    Route::put('/user/update', 'update');
    Route::post('/user/lock', 'lockUser');
    Route::post('/users/department', 'showUsersByDepartment');
    Route::post('/users/type', 'showUsersByType');
});


Route::controller(ServiceController::class)->group(function () {
    Route::get('/services', 'index');
    Route::get('/service/{id}', 'show');
    Route::post('/service/save', 'store');
    Route::put('/service/update', 'update');
    Route::delete('/service/delete', 'destroy');
});

Route::controller(LanguageController::class)->group(function () {
    Route::get('/lenguajes', 'index');
    Route::get('/language/fields', 'getFieldsToTranslate');
});