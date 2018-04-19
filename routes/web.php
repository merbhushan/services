<?php
// use App\setting;
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
    // return bcrypt('bhushan');
    return view('welcome');
});

Route::get('/setting', function(){
    return ('test' .'123');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
