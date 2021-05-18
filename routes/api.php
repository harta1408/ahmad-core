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
Route::get('donatur/list','APIDonatur@getDonaturList');
Route::get('donatur/byid/{id}','Donatur@getDonaturById');
Route::post('donatur/save/','Donatur@saveDonatur');

Route::post('santri/register','SantriAPI@registerSantri');
Route::get('santri/byemail/{email}','SantriAPI@getSantriByEmail');
Route::get('santri/list','SantriAPI@getSantriList');
Route::get('santri/byid/{id}','SantriAPI@getSantriById');
Route::post('santri/save','SantriAPI@saveSantri');

Route::get('produk/list','Produk@getProdukList');
Route::get('produk/byid/{id}','Produk@getProdukById');
Route::put('produk/save/','Produk@saveProduk');
