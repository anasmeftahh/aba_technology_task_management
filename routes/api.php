<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::controller(ProjectController::class)->group(function () {
    Route::get('projects', 'index');
    Route::post('project', 'store');
    Route::get('project/{id}', 'show');
    Route::put('project/{id}', 'update');
    Route::delete('project/{id}', 'destroy');
    Route::get('projects/manager/{id}', 'showManagerProjects');
}); 

Route::controller(TaskController::class)->group(function () {
    Route::get('tasks', 'index');
    Route::post('task', 'store');
    Route::get('task/{id}', 'show');
    Route::get('task/{id}/progress', 'getTaskProgress');
    Route::put('task/{id}', 'update');
    Route::delete('task/{id}', 'destroy');
    Route::get('tasks/member/{id}', 'showMembertasks');
}); 

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
