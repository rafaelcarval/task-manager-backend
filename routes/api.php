<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

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


Route::post('/auth/register',       [UserController::class, 'createUser']);
Route::post('/auth/login',          [UserController::class, 'loginUser']);

Route::middleware(['auth:sanctum'])->group(function () {  

    Route::post('/task',                    [TaskController::class, 'store']);
    Route::get('/tasks',                    [TaskController::class, 'tasks']);
    Route::post('/task/update',               [TaskController::class, 'update']);
    Route::post('/task/{task}/finished',    [TaskController::class,'finished']);

    Route::put('/user/update',          [UserController::class, 'updateUser']);
    Route::get('/user',                 [UserController::class, 'user']);
    Route::get('/users',                [UserController::class, 'users']);
    Route::post('/logout',              [UserController::class, 'logout']);

});