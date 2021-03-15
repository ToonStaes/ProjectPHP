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

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('password', 'User\PasswordController@edit');
    Route::post('password', 'User\PasswordController@update');
});

Route::middleware(['auth'])->prefix('financial_employee')->group(function(){
    Route::get('Mailcontent/qryMailcontents', 'financial_employee\MailcontentController@qryMailcontents');
    Route::resource('Mailcontent', 'financial_employee\MailcontentController');
//    Route::put('Mailcontent/{id}', 'financial_employee\MailcontentController@update');
});

//Route::get('/users/getUser/{$id}', 'financial_employee\UserController@getUser');
Route::get('/users/getUsers', 'financial_employee\UserController@getUsers');
Route::resource('users', 'financial_employee\UserController');
Route::resource('kostenplaats', 'financial_employee\Cost_center_controller');
