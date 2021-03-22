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
    Route::get('mijnaanvragen', 'user\AanvraagController@index');
    Route::get('mijnaanvragen/qryRequests', 'user\AanvraagController@qryRequests');
});

Route::get('/aanvragen_beheren', 'cost_center_manager\RequestController@index');
Route::get('/getRequests', 'cost_center_manager\RequestController@getRequests');

Route::middleware(['auth', 'changed_password' ,'financial_employee'])->group(function () {
  Route::get('/users/getUsers', 'financial_employee\UserController@getUsers');
  Route::get('/users/getProgrammes', 'financial_employee\UserController@getProgrammes');
  Route::resource('users', 'financial_employee\UserController');
  Route::resource('cost_centers', 'financial_employee\Cost_center_controller');
  Route::get('Mailcontent/qryMailcontents', 'financial_employee\MailcontentController@qryMailcontents');
  Route::resource('Mailcontent', 'financial_employee\MailcontentController',['parameters' => ['Mailcontent' => 'mailcontent']]);
});
