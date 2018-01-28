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

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/create', 'ThreadsController@create');
Route::get('/threads/{channel?}', 'ThreadsController@index');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profiles');
// Route::resource('threads', 'ThreadsController');

Route::post('threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/replies/{reply}/favourites', 'FavouritesController@store');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
