<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['cors'])->group(function () {
    Route::post('user/login','userAPI@userLogin');
    Route::put('user/change/password','userAPI@userChangePassword');
    
    Route::post('donatur/register','DonaturAPI@donaturRegister');
    Route::post('donatur/register/sosmed','DonaturAPI@donaturRegisterSosmed');
    Route::put('donatur/update/profile/{id}','DonaturAPI@donaturUpdateProfile');
    Route::get('donatur/byemail/{email}','DonaturAPI@donaturByEmail');
    Route::get('donatur/list','DonaturAPI@donaturList');
    Route::get('donatur/byid/{id}','DonaturAPI@donaturById');
    
    Route::post('santri/register','SantriAPI@santriRegister');
    Route::post('santri/register/sosmed','SantriAPI@santriRegisterSosmed');
    Route::put('santri/update/profile/{id}','SantriAPI@santriUpdateProfile');
    Route::get('santri/byemail/{email}','SantriAPI@santriByEmail');
    Route::get('santri/list','SantriAPI@santriList');
    Route::get('santri/byid/{id}','SantriAPI@santriById');
    
    Route::post('produk/save','ProdukAPI@produkSimpan');
    Route::get('produk/list','ProdukAPI@getProdukList');
    Route::get('produk/byid/{id}','ProdukAPI@getProdukById');
    
    
    Route::post('kuesioner/simpan','KuesionerAPI@kuesionerSimpan');
    Route::get('kuesioner/list','KuesionerAPI@kuesionerList');
    Route::post('kuesioner/santri/simpan','KuesionerAPI@kuesionerSantriSimpan');
});



