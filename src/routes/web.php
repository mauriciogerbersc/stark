<?php

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
Auth::routes();


Route::get('/', 'SalaController@index');
Route::get('/salas', 'SalaController@listarSalas')->name('listar_salas');
Route::post('/sala/store', 'SalaController@store')->name('salvar_salas');
Route::get('/sala/{sala_id}', 'SalaController@show')->name('listar_visitantes_da_sala');


Route::get('/visitantes', 'VisitanteController@index');
Route::post('/visitantes/store', 'VisitanteController@store')->name('salvar_visitantes');
Route::get('/visitantes/{sala_visitante}/out', 'VisitanteController@out');
//Route::get('/', 'HomeController@index')->name('home');
