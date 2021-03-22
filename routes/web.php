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
    return view('test');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::redirect('home', '/');
Route::view('/', 'home');

Route::get('user/firstPassword', 'User\PasswordController@edit');
Route::post('user/password', 'User\PasswordController@update');

Route::middleware(['auth', 'changed_password'])->prefix('user')->group(function () {
    Route::get('password', 'User\PasswordController@edit');
    Route::view('laptop', 'user.laptop');
    Route::post('laptop', 'user\LaptopController@store');
    Route::view('divers', 'user.diverse');
    Route::post('divers', 'user\DiverseController@store');
});

Route::middleware(['auth', 'changed_password' ,'financial_employee'])->group(function () {
  Route::get('/users/getUsers', 'financial_employee\UserController@getUsers');
  Route::get('/users/getProgrammes', 'financial_employee\UserController@getProgrammes');
  Route::resource('users', 'financial_employee\UserController');
});

Route::resource('kostenplaats', 'financial_employee\Cost_center_controller');
