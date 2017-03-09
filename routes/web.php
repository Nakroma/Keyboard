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

Route::get('/', function () {
    return view('auth/login');
})->middleware('guest');

Auth::routes();

/* Main board routes */
Route::get('board', 'HomeController@index');
Route::get('thread', 'HomeController@threadForm');
Route::post('thread', 'HomeController@createThread');
Route::delete('thread/delete/{id}', 'HomeController@deleteThread');

/* Thread routes */
Route::get('thread/{id}', 'ThreadController@index');
Route::post('post', 'ThreadController@createPost');
Route::get('post/delete/{id}', 'ThreadController@deletePost');  // Really a delete but whatever

/* Profile routes */
Route::get('profile', 'ProfileController@index');
Route::post('user/ban', 'ProfileController@banUser');
Route::post('user/promote', 'ProfileController@promoteUser');
Route::post('key/generate', 'ProfileController@generateKey');