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
    return view('welcome');
});

// Route::resource('email', 'EmailController');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['prefix' => 'superadmin', 'middleware' => ['role:super-admin']], function() {
    Route::resource('users','UserController'); //crud route

    Route::post('users/main','UserController@userMain')->name('users.main');


    Route::get('users/approve/index','UserController@userApproveIndex')->name('users.approve.index');
    Route::get('users/approve/load','UserController@userApprovalLoad')->name('users.approve.load');
    Route::put('users/approve/update/{id}','UserController@userApprovalUpdate');

}); 
Route::group(['prefix' => 'dashboard', 'middleware' => ['role:manajer|helpdesk']], function() {
    Route::resource('donatur', 'DonaturController');

    Route::get('kodepos/provinsi/all','KodePosController@kodeposProvinsiAll');
}); 
