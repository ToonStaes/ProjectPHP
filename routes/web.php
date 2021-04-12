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


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::redirect('home', '/');
Route::view('/', 'home');

Route::get('user/firstPassword', 'User\PasswordController@edit');
Route::post('user/password', 'User\PasswordController@update');
Route::post('password/reset', 'User\PasswordController@reset');

Route::middleware(['auth', 'changed_password'])->prefix('user')->group(function () {
    Route::get('password', 'User\PasswordController@edit');
    Route::view('laptop', 'user.laptop');
    Route::post('laptop', 'user\LaptopController@store');
    Route::get('request_bike_reimbursement', 'User\BikerideController@index');
    Route::post('save_bikerides', 'User\BikerideController@store');
    Route::post('request_bikeReimbursement', 'User\BikeReimbursementController@store');
});

Route::middleware(['auth', 'changed_password' ,'cost_center_manager'])->group(function () {
    Route::get('/aanvragen_beheren', 'cost_center_manager\RequestController@index');
    Route::get('/getRequests', 'cost_center_manager\RequestController@getRequests');
    Route::put('/saveComment', 'cost_center_manager\RequestController@saveComment');
});

Route::middleware(['auth', 'changed_password' ,'financial_employee'])->group(function () {
  Route::get('/users/getUsers', 'financial_employee\UserController@getUsers');
  Route::get('/users/getProgrammes', 'financial_employee\UserController@getProgrammes');
  Route::resource('users', 'financial_employee\UserController');
  Route::resource('cost_centers', 'financial_employee\Cost_center_controller');
  Route::get('Mailcontent/qryMailcontents', 'financial_employee\MailcontentController@qryMailcontents');
  Route::resource('Mailcontent', 'financial_employee\MailcontentController',['parameters' => ['Mailcontent' => 'mailcontent']]);
  Route::resource('parameters', 'financial_employee\ParameterController');
});

Route::middleware(['auth', 'changed_password' ,'financial_employee'])->prefix('financial_employee')->group(function () {
    Route::get('/aanvragen_beheren', 'financial_employee\RequestController@index');
    Route::get('/getRequests', 'financial_employee\RequestController@getRequests');
    Route::get('/getOpenPayments', 'financial_employee\RequestController@getOpenPayments');
    Route::post('/payOpenPayments', 'financial_employee\RequestController@payOpenPayments');
    Route::put('/saveComment', 'financial_employee\RequestController@saveComment');
});
