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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Threads routes
 */
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads', 'ThreadsController@store')->middleware('needs-verified-email');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel?}', 'ThreadsController@index')->name('threads');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::post('/threads/{channel}/{thread}/subscribe', 'SubscriptionsController@store');
Route::delete('/threads/{channel}/{thread}/subscribe', 'SubscriptionsController@destroy');


/**
 * Replies routes
 */
Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::get('threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');

/**
 * Favourites routes
 */
Route::post('/replies/{reply}/favourites', 'FavouritesController@store');
Route::delete('/replies/{reply}/favourites', 'FavouritesController@destroy');

/**
 * Users Routes
 */
Auth::routes();
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profiles');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatars', 'Api\UsersAvatarsController@store')->name('avatars');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/register/confirmation', 'Auth\RegisterConfirmationController@index')->name('register.confirm');