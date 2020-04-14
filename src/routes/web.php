<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Laravel-Revolut requires a web route to receive authorization codes from
| Revolut.
|
*/

Route::group(['namespace' => 'tbclla\Revolut\Controllers'], function() {
    Route::get(parse_url(config('revolut.redirect_uri'))['path'], 'AuthorizationController');
});
