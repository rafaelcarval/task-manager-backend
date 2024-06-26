<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use OpenApi\Generator;

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
Route::get('/swagger.json', function () {
    header('Content-Type: application/x-yaml');
    return Generator::scan([ app_path(), ])->toJson();
});

Route::post('/auth/register',       [UserController::class, 'createUser']);
Route::post('/auth/login',          [UserController::class, 'loginUser']);

Route::middleware(['auth:sanctum'])->group(function () {  

    Route::post('/task',                            [TaskController::class, 'store']);
    Route::get('/tasks',                            [TaskController::class, 'tasks']);
    Route::post('/tasksnetweendates',               [TaskController::class, 'tasksBetweenDates']);
    Route::post('/tasksfiltersearch',               [TaskController::class, 'tasksFilterSearch']);
    Route::patch('/task/update',                    [TaskController::class, 'update']);
    Route::post('/task/{task}/finished',            [TaskController::class, 'finished']);
    Route::delete('/task/{task}',                   [TaskController::class, 'delete']);

    Route::patch('/user/update',                    [UserController::class, 'updateUser']);
    Route::get('/user',                             [UserController::class, 'user']);
    Route::get('/users',                            [UserController::class, 'users']);
    Route::post('/usersfiltersearch',               [UserController::class, 'usersFilterSearch']);
    Route::post('/logout',                          [UserController::class, 'logout']);
    Route::delete('/user/{user}',                   [TaskController::class, 'delete']);

});