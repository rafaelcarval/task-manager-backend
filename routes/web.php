<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-docs', function () {
    $swagger = \OpenApi\Generator::scan([app_path()]);
    return $swagger->toYaml();
    //return response()->json($swagger);
});

Route::get('/api-docs-ui', function () {
    return view('swagger');
});