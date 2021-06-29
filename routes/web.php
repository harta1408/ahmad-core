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
Route::get('/register/{hashcode}');

Route::group(['prefix' => 'superadmin', 'middleware' => ['role:super-admin']], function() {
    Route::resource('users','UserController'); //crud route

    Route::post('users/main','UserController@userMain')->name('users.main');


    Route::get('users/approve/index','UserController@userApproveIndex')->name('users.approve.index');
    Route::get('users/approve/load','UserController@userApprovalLoad')->name('users.approve.load');
    Route::put('users/approve/update/{id}','UserController@userApprovalUpdate');
}); 

Route::group(['prefix' => 'dashboard', 'middleware' => ['role:manajer|helpdesk']], function() {
    Route::get('helpdesk/index', "DashboardController@dashHelpDeskIndex")->name('dashboard.helpdesk.index');
    
    Route::resource('lembaga', 'LembagaController');
    Route::resource('faq', 'FAQController');
    Route::resource('rekeningbank', 'RekeningBankController');

    Route::resource('donatur', 'DonaturController');
    Route::get('donatur/pembaharuan/index','DonaturController@donaturRenewIndex')->name('donatur.pembaharuan.index');
    Route::post('donatur/pembaharuan/main','DonaturController@donaturRenewMain')->name('donatur.pembaharuan.main');
    Route::get('donatur/simple/list','DonaturController@donaturSimpleList')->name('donatur.simple.list');

    Route::resource('santri', 'SantriController');
    Route::get('santri/pembaharuan/index','SantriController@santriRenewIndex')->name('santri.pembaharuan.index');
    Route::post('santri/pembaharuan/main','SantriController@santriRenewMain')->name('santri.pembaharuan.main');
    Route::get('santri/otorisasi/load','SantriController@SantriOtorisasiLoad')->name('santri.otorisasi.load');
    Route::put('santri/otorisasi/update/{id}','SantriController@santriOtorisasiUpdate')->name('santri.otorisasi.update');
    Route::get('santri/otoriasasi/index','SantriController@santriOtorisasiIndex')->name('santri.otorisasi.index');
    Route::get('santri/simple/list','SantriController@santriSimpleList')->name('santri.simple.list');

    Route::resource('pendamping', 'PendampingController');
    Route::get('pendamping/pembaharuan/index','PendampingController@pendampingRenewIndex')->name('pendamping.pembaharuan.index');
    Route::post('pendamping/pembaharuan/main','PendampingController@pendampingRenewMain')->name('pendamping.pembaharuan.main');
    Route::get('pendamping/otorisasi/load','PendampingController@pendampingOtorisasiLoad')->name('pendamping.otorisasi.load');
    Route::put('pendamping/otorisasi/update/{id}','PendampingController@pendampingOtorisasiUpdate')->name('pendamping.otorisasi.update');
    Route::get('pendamping/otoriasasi/index','PendampingController@pendampingOtorisasiIndex')->name('pendamping.otorisasi.index');
    Route::get('pendamping/simple/list','PendampingController@pendampingSimpleList')->name('pendamping.simple.list');

    
    Route::resource('produk', 'ProdukController');
    Route::resource('kirimproduk', 'KirimProdukController');

    Route::resource('pengingat', 'PengingatController');
    Route::post('pengingat/main','PengingatController@main')->name('pengingat.main');

    Route::resource('berita','BeritaController');
    Route::post('berita/main','BeritaController@main')->name('berita.main');
    Route::post('berita/send','BeritaController@send')->name('berita.send');

    Route::resource('hadist', 'HadistController');
    Route::post('hadist/main','HadistController@main')->name('hadist.main');
    Route::post('hadist/send/main','HadistController@mainsend')->name('hadist.send.main');
    Route::post('hadist/send','HadistController@send')->name('hadist.send');

    Route::resource('pesan', 'PesanController');
    Route::post('pesan/main','PesanController@main')->name('pesan.main');
    Route::post('pesan/new/menu','PesanController@newmenu')->name('pesan.new.menu');
    Route::post('pesan/new','PesanController@newpesan')->name('pesan.new');

    Route::resource('referral', 'ReferralController');
    Route::post('referral/new/menu/index','ReferralController@newmenuindex')->name('referral.new.menu.index');
    Route::post('referral/new/menu/','ReferralController@newmenu')->name('referral.new.menu');
    Route::post('referral/main','ReferralController@main')->name('referral.main');

    Route::resource('materi','MateriController');
    Route::resource('kuesioner', 'KuesionerController');
    Route::resource('soal','SoalController');
    Route::post('soal/main','SoalController@main')->name('soal.main');
    Route::post('soal/new/menu','SoalController@soalNewMenu')->name('soal.new.menu');

    Route::get('kodepos/provinsi/all','KodePosController@kodeposProvinsiAll');
    Route::get('kodepos/kota/{provinsi}','KodePosController@kodeposKotaByProvinsi');
    Route::get('kodepos/kabupaten/{kota}','KodePosController@kecamatanByKota');
    Route::get('kodepos/kelurahan/{kabupaten}','KodePosController@kelurahanByKecamatan');
}); 
