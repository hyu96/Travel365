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

Route::get('/', "TripController@index")->name('homepage');
Route::get('/home', 'TripController@index')->name('home');

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::post('/follow', 'FollowController@follow');
Route::post('/unfollow', 'FollowController@unfollow');

Route::post('/join', 'JoinController@join');
Route::post('/join/cancel', 'JoinController@cancel');
Route::post('/join/accept', 'JoinController@accept');
Route::post('/join/refuse', 'JoinController@refuse');
Route::post('/join/outTrip', 'JoinController@outTrip');
Route::post('/join/kick', 'JoinController@kick');

Route::get('/users/notification', 'UserController@noti')->middleware('auth')->name('users.noti');

Route::post('/trips/cancel', 'TripController@cancelTrip');
Route::post('/trips/start', 'TripController@startTrip');
Route::post('/trips/finish', 'TripController@finishTrip');

Route::resource('trips', 'TripController');

