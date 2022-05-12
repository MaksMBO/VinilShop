<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;

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
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/information', function () {
    return view('information_page');
})->name('information');

Route::get('/records', function () {
    return view('records');
})->name('records');

Route::post('/register/signup', 'usersController@submit')->name('signup');

Route::post('/login/signin', 'usersController@allData')->name('signin');

Route::get("/lending", 'TurntablesController@turntablesForLending')->name('lending');

Route::get('/turntables', 'TurntablesController@turntablesAll')->name('turntables');
Route::get('/turntables/search', 'TurntablesController@turntablesSearch')->name('turntablesSearch');
