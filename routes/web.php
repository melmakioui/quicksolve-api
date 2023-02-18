<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IncidenceController;
use App\Http\Resources\IncidenceResource;
use App\Models\Incidence;

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

//Spaces
Route::controller(SpaceController::class)->group(function () {

    Route::get('/spaces', 'index');

    Route::get('/space/{id}', 'show');

    Route::post('/space/save', 'store');

    Route::put('/space/update', 'update');

    Route::delete('/space/delete', 'destroy');
});

//Departments

Route::controller(DepartmentController::class)->group(function () {

    Route::get('/departments', 'index');

    Route::get('/department/{id}', 'show');

    Route::post('/department/save', 'store');

    Route::put('/department/update', 'update');

    Route::delete('/department/delete', 'destroy');

    Route::post('/department/type', 'getByDepartmentByType');

    Route::put('/department/update/type', 'updateDepartmentType');
});


//Incidences

Route::controller(IncidenceController::class)->group(function () {

    Route::get('/incidences', function(){
        return IncidenceResource::collection(Incidence::all()->load('userIncidences.user'));
    });

    Route::get('/incidence/{id}', 'show');

    // Route::post('/department/save', 'store');

    // Route::put('/department/update', 'update');

    Route::delete('/incidence/close', 'destroy');

    // Route::post('/department/type', 'getByDepartmentByType');

    // Route::put('/department/update/type', 'updateDepartmentType');
});

