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
    Route::post('user/login','UserAPI@userLogin');
    Route::post('user/login/gmail','UserAPI@userLoginGMail');
    Route::put('user/change/password/{id}','UserAPI@userChangePassword');
    Route::put('user/verification/{id}','UserAPI@userVerification');
    Route::get('user/byhashcode/{hashcode}','UserAPI@userByHashCode');

    Route::get('lembaga','LembagaAPI@getLembaga');
    Route::get('lembaga/rekening/bank','LembagaAPI@getRekeningBankList');
    
    Route::post('donatur/register','DonaturAPI@donaturRegister');
    Route::post('donatur/register/donasi','DonaturAPI@donaturRegisterDonasi');
    Route::post('donatur/register/referral','DonaturAPI@donaturRegisterReferral');
    Route::post('donatur/register/donasi/referral','DonaturAPI@donaturRegisterDonasiReferral');
    Route::post('donatur/register/gmail','DonaturAPI@donaturRegisterGMail');

    Route::put('donatur/update/profile/{id}','DonaturAPI@donaturUpdateProfile');
    Route::get('donatur/byemail/{email}','DonaturAPI@donaturByEmail');
    Route::get('donatur/list','DonaturAPI@donaturList');
    Route::get('donatur/byid/{id}','DonaturAPI@donaturById');
    Route::post('donatur/upload/photo','DonaturAPI@donaturUploadImage');
    
    Route::post('santri/register','SantriAPI@santriRegister');
    Route::post('santri/register/sosmed','SantriAPI@santriRegisterSosmed');
    Route::put('santri/update/profile/{id}','SantriAPI@santriUpdateProfile');
    Route::get('santri/byemail/{email}','SantriAPI@santriByEmail');
    Route::get('santri/list','SantriAPI@santriList');
    Route::get('santri/byid/{id}','SantriAPI@santriById');
    
    Route::post('produk/save','ProdukAPI@produkSimpan');
    Route::get('produk/byid/{id}','ProdukAPI@produkById');

    Route::post('rekening/save','RekeningAPI@rekeningSimpan');
    Route::put('rekening/update/{id}','RekeningAPI@rekeningUpdate');
    Route::get('rekening/byid/{id}','RekeningAPI@rekeningById');
    Route::get('rekening/list','RekeningAPI@rekeningList');
    
    Route::post('donasi/temp/save','DonasiAPI@donasiTempSimpan');
    Route::post('donasi/save','DonasiAPI@donasiSimpan');
    Route::get('donasi/byid/{id}','DonasiAPI@donasiById');

    Route::post('berita/save','BeritaAPI@beritaSimpan');
    Route::put('berita/update/{id}','BeritaAPI@beritaUpdate');
    Route::get('berita/kampanye','BeritaAPI@beritaKampanye');
    Route::get('berita/entitas/{jenis}','BeritaAPI@beritaEntitas');
    Route::get('berita/list','BeritaAPI@beritaList');

    Route::post('referral/send/link','ReferralAPI@referralSendLink');
    Route::put('referral/update/berita','ReferralAPI@rreferralUpdateIdBerita');

    Route::post('pengingat/save','pengingatAPI@pengingatSimpan');
    Route::put('pengingat/update/{id}','pengingatAPI@pengingatUpdate');
    Route::get('pengingat/jenis/{jenis}','pengingatAPI@pengingatJenis');
    Route::get('pengingat/list','pengingatAPI@pengingatList');

    Route::post('kuesioner/simpan','KuesionerAPI@kuesionerSimpan');
    Route::put('kuesioner/update/{id}','KuesionerAPI@kuesionerUpdate');
    Route::get('kuesioner/list','KuesionerAPI@kuesionerList');
    Route::get('kuesioner/entitas/{entitas}','KuesionerAPI@kuesionerByEntitas');
    Route::post('kuesioner/santri/simpan','KuesionerAPI@kuesionerSantriSimpan');

    Route::post('message/send/wa','MessageAPI@sendWhatsApp');

    Route::get('kodepos/list/provinsi/all','KodePosAPI@kodeposProvinsiAll');
    Route::get('kodepos/list/provinsi/{provinsi}','KodePosAPI@kodeposProvinsi');
    Route::get('kodepos/list/kota/{kota}','KodePosAPI@kodeposKota');
    Route::get('kodepos/list/kecamatan/{kecamatan}','KodePosAPI@kodeposKecamatan');
    Route::get('kodepos/list/kelurahan/{kelurahan}','KodePosAPI@kodeposKelurahan');
    Route::get('kodepos/list/kodepos/{kodepos}','KodePosAPI@kodeposKodePos');
    Route::get('kodepos/kotabyprovinsi/{provinsi}','KodePosAPI@kotaByProvinsi');
    Route::get('kodepos/kecamatanbykota/{kota}','KodePosAPI@kecamatanByKota');
    Route::get('kodepos/kelurahanbykecamatan/{kecamatan}','KodePosAPI@kelurahanByKecamatan');
    Route::get('kodepos/kodeposbykelurahan/{kelurahan}','KodePosAPI@kodeposByKelurahan');
});



