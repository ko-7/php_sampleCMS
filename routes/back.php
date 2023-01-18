<?php
use Illuminate\Support\Facades\Route;
 
Route::get('/', 'DashboardController')->name('dashboard');
Route::group(['middleware' => 'can:admin'], function () {
  Route::resource('posts', 'PostController')->except('show');
});