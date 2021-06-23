<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function() {

    //Role listing with datatable
    Route::get('get-github-token', 'GitHubController@getToken');
    Route::post('save-github-token', 'GitHubController@saveToken');
    Route::get('get-github-starred-repo', 'GitHubController@getGithubStarredRepo');
    
});
