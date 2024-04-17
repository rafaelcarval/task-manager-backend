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

Route::get('/generate-api-docs', function () {
    ob_start(); // Inicia o buffer de saída
    require_once '../generate-api-docs.php'; // Inclui o arquivo generate-api-docs.php
    $content = ob_get_clean(); // Limpa o buffer de saída e o retorna como uma string
    return response($content)->header('Content-Type', 'application/json'); // Retorna a resposta HTTP com o conteúdo do buffer de saída como JSON
});


Route::get('/api-docs', function () {
    $swagger = \OpenApi\Generator::scan([app_path()]);
    return $swagger->toYaml();
    //return response()->json($swagger);
});

Route::get('/api-docs-ui', function () {
    return view('swagger');
});