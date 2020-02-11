<?php

use Illuminate\Support\Facades\Route;

/**
 *  - Por defecto CORS estara activo para toodas las rutas
 *  - Creado grupo de rutas que debe estan autenticadas
 */
Route::post('login', 'APIController@login');
Route::post('register', 'APIController@register');

Route::group(['middleware' => 'auth.jwt'], function () {

    Route::get('user', 'APIController@getAuthenticatedUser');

    Route::get('listasAdmin', 'api\listasController@listasAdmin');

    Route::get('listas', 'api\listasController@getLista')->name('getListas');
    Route::get('listas/{id}', 'api\listasController@infoLista')->name('getLista');
    Route::post('listas', 'api\listasController@addLista')->name('addLista');
    Route::put('listas/{id}', 'api\listasController@editLista')->name('editLista');
    Route::delete('listas/{id}', 'api\listasController@delList')->name('delLista');

    // - Admin -> controlar
    Route::get('users', 'api\usuariosController@getUsers')->name('getUsers');
    Route::get('users/{id}', 'api\usuariosController@infoUser')->name('getUser');
    Route::post('users', 'api\usuariosController@addUser')->name('addUser');
    Route::put('users/{id}', 'api\usuariosController@editUser')->name('editUser');
    Route::delete('users/{id}', 'api\usuariosController@delUser')->name('delUser');
});
