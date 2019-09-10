<?php

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

Route::get('/', 'HomeController@index');

Auth::routes([
    'verify' => true,
    'register' => false,
]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
});
