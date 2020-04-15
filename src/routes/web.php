<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Laravel-Revolut requires two web routes to request and receive
| authorization codes from Revolut.
|
*/

Route::group(['namespace' => 'tbclla\Revolut\Controllers', 'middleware' => ['web']], function() {

    $route = parse_url(config('revolut.redirect_uri'))['path'];

    Route::get($route . '/create', 'AuthorizationController@create')
         ->middleware(config('revolut.auth_route.middleware'))
         ->name(config('revolut.auth_route.name'));

    Route::get($route, 'AuthorizationController@store');
});
