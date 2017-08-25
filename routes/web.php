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

Route::prefix('join')->group(function () {
	Route::post('/', 'JoinController@join');
	Route::post('/cancel', 'JoinController@cancel');
	Route::post('/accept', 'JoinController@accept');
	Route::post('/refuse', 'JoinController@refuse');
	Route::post('/outTrip', 'JoinController@outTrip');
	Route::post('/kick', 'JoinController@kick');
});

Route::prefix('users')->group(function () {
	Route::get('/notification', 'UserController@noti')->middleware('auth')->name('users.noti');
	Route::get('/{id}', 'UserController@show')->name('users.profile');
	Route::get('/{id}/edit', 'UserController@edit')->name('users.edit');
	Route::post('/update', 'UserController@update')->name('users.update');
});


Route::prefix('trips')->group(function () {
    Route::post('/cancel', 'TripController@cancel');
	Route::post('/start', 'TripController@start');
	Route::post('/finish', 'TripController@finish');
	Route::post('/update', 'TripController@update');
});

Route::post('/comment/store', "CommentController@store");


Route::resource('trips', 'TripController', ['except' => [
   	'update', 'destroy'
]]);

