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
    // return view('welcome');
    // return view('welcome');
    return redirect('/login');

});

// Route::resource('email', 'EmailController');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/register/{hashcode}');

Route::group(['prefix' => 'superadmin', 'middleware' => ['role:super-admin']], function() {
    Route::resource('users','UserController'); //crud route

    Route::post('users/main','UserController@userMain')->name('users.main');
    Route::put('users/password/reset/{id}','UserController@userPasswordReset')->name('users.password.reset');


    Route::get('users/approve/index','UserController@userApproveIndex')->name('users.approve.index');
    Route::get('users/approve/load','UserController@userApprovalLoad')->name('users.approve.load');
    Route::put('users/approve/update/{id}','UserController@userApprovalUpdate');
}); 

Route::group(['prefix' => 'dashboard', 'middleware' => ['role:manajer|helpdesk|super-admin']], function() {
    Route::get('helpdesk/index', "DashboardController@dashHelpDeskIndex")->name('dashboard.helpdesk.index');
    
    Route::resource('lembaga', 'LembagaController');
    Route::resource('faq', 'FAQController');
    Route::resource('rekeningbank', 'RekeningBankController');
    Route::get('lembaga/hijriah/index','LembagaController@hijriahIndex')->name('lembaga.hijriah.index');
    Route::get('lembaga/hijriah/update/{adjust}','LembagaController@hijriahUpdate')->name('lembaga.hijriah.update');
    Route::post('lembaga/hijriah/save','LembagaController@hijriahSave')->name('lembaga.hijriah.save');
    Route::resource('hadiah', 'HadiahController');
    Route::post('hadiah/main','HadiahController@main')->name('hadiah.main');

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
    Route::get('pendamping/pembaharuan/index','PendampingController@pendampingUpdateIndex')->name('pendamping.pembaharuan.index');
    Route::post('pendamping/pembaharuan/main','PendampingController@pendampingUpdateMain')->name('pendamping.pembaharuan.main');
    Route::get('pendamping/otorisasi/load','PendampingController@pendampingOtorisasiLoad')->name('pendamping.otorisasi.load');
    Route::put('pendamping/otorisasi/update/{id}','PendampingController@pendampingOtorisasiUpdate')->name('pendamping.otorisasi.update');
    Route::get('pendamping/otorisasi/index','PendampingController@pendampingOtorisasiIndex')->name('pendamping.otorisasi.index');
    Route::get('pendamping/simple/list','PendampingController@pendampingSimpleList')->name('pendamping.simple.list');

    Route::resource('produk', 'ProdukController');
    Route::get('kirimproduk/load','KirimProdukController@load')->name('kirimproduk.load');
    Route::post('kirimproduk/main','KirimProdukController@main')->name('kirimproduk.main');
    Route::get('kirimproduk/lacak/load','KirimProdukController@lacakload')->name('kirimproduk.lacak.load');
    Route::get('kirimproduk/lacak/index','KirimProdukController@lacakindex')->name('kirimproduk.lacak.index');
    Route::post('kirimproduk/lacak/main','KirimProdukController@lacakmain')->name('kirimproduk.lacak.main');
    Route::get('kirimprodtuk/lacak/hasil/{id}','KirimProdukController@lacakhasil')->name('kirimproduk.lacak.hasil');
    Route::resource('kirimproduk', 'KirimProdukController'); 

    Route::resource('pengingat', 'PengingatController');
    Route::post('pengingat/main','PengingatController@main')->name('pengingat.main');
    Route::get('pengingat/donatur/index','PengingatController@pengingatDonaturIndex')->name('pengingat.donatur.index');
    Route::get('pengingat/donatur/load','PengingatController@pengingatDonaturLoad')->name('pengingat.donatur.load');
    Route::post('pengingat/donatur/main','PengingatController@pengingatDonaturMain')->name('pengingat.donatur.main');
    Route::get('pengingat/santri/index','PengingatController@pengingatSantriIndex')->name('pengingat.santri.index');
    Route::get('pengingat/santri/load','PengingatController@pengingatSantriLoad')->name('pengingat.santri.load');
    Route::post('pengingat/santri/main','PengingatController@pengingatSantriMain')->name('pengingat.santri.main');
    Route::get('pengingat/pendamping/santri/index','PengingatController@pengingatPendampingIndex')->name('pengingat.pendamping.index');
    Route::post('pengingat/pendamping/santri/main','PengingatController@pengingatPendampingMain')->name('pengingat.pendamping.main');
    Route::get('pengingat/video/index','PengingatController@pengingatVideoIndex')->name('pengingat.video.index');
    Route::get('pengingat/video/load','PengingatController@pengingatVideoLoad')->name('pengingat.video.load');

    Route::resource('berita','BeritaController');
    Route::post('berita/main','BeritaController@main')->name('berita.main');
    Route::post('berita/send','BeritaController@send')->name('berita.send');
    Route::get('berita/kampanye/index','BeritaController@beritaKampanyeIndex')->name('berita.kampanye.index');
    Route::get('berita/kampanye/load','BeritaController@beritaKampanyeLoad')->name('berita.kampanye.load');
    Route::post('berita/kampanye/main','BeritaController@beritaKampanyeMain')->name('berita.kampanye.main');
    Route::post('berita/kampanye/save','BeritaController@beritaKampanyeSave')->name('berita.kampanye.save');
    Route::put('berita/kampanye/update/{id}','BeritaController@beritaKampanyeUpdate')->name('berita.kampanye.update');
    Route::get('berita/video/index','BeritaController@beritaVideoIndex')->name('berita.video.index');
    Route::get('berita/video/load','BeritaController@beritaVideoLoad')->name('berita.video.load');

    Route::resource('hadist', 'HadistController');
    Route::post('hadist/main','HadistController@main')->name('hadist.main');
    Route::post('hadist/send/main','HadistController@mainsend')->name('hadist.send.main');
    Route::post('hadist/send','HadistController@send')->name('hadist.send');
    Route::get('hadist/video/list','HadistController@hadistVideoIndex')->name('hadist.video.index');

    Route::resource('pesan', 'PesanController');
    Route::post('pesan/main','PesanController@main')->name('pesan.main');
    Route::post('pesan/new/menu','PesanController@newmenu')->name('pesan.new.menu');
    Route::post('pesan/new','PesanController@newpesan')->name('pesan.new');

    Route::resource('referral', 'ReferralController');
    Route::post('referral/new/menu/index','ReferralController@newmenuindex')->name('referral.new.menu.index');
    Route::post('referral/new/menu/','ReferralController@newmenu')->name('referral.new.menu');
    Route::post('referral/main','ReferralController@main')->name('referral.main');
    Route::get('referral/konten/index','ReferralController@referralKontenIndex')->name('referral.konten.index');
    Route::post('referral/konten/main','ReferralController@referralKontenMain')->name('referral.konten.main');
    Route::put('referral/konten/update/{id}','ReferralController@referralKontenUpdate')->name('referral.konten.update');

    Route::resource('materi','MateriController');
    Route::resource('kuesioner', 'KuesionerController');
    
    Route::resource('soal','SoalController');
    Route::post('soal/main','SoalController@main')->name('soal.main');
    Route::post('soal/new/menu','SoalController@soalNewMenu')->name('soal.new.menu');

    Route::resource('donasi', 'DonasiController');
    Route::get('donasi/donatur/byid/{donaturid}','DonasiController@donasiByDonaturId')->name('donasi.donatur.id');
    Route::get('donasi/pending/index','DonasiController@donasiPendingIndex')->name('donasi.pending.index');
    Route::get('donasi/pending/list','DonasiController@donasiPendingList')->name('donasi.pending.list');
    Route::get('donasi/pending/load','DonasiController@donasiPendingLoad')->name('donasi.pending.load');
    Route::put('donasi/pending/update/{id}','DonasiController@donasiPendingUpdate')->name('donasi.pending.update');
    Route::get('donasi/random/index','DonasiController@donasiRandomIndex')->name('donasi.random.index');
    Route::get('donasi/random/load','DonasiController@donasiRandomLoad')->name('donasi.random.load');
    Route::post('donasi/random/main','DonasiController@donasiRandomMain')->name('donasi.random.main');
    Route::post('donasi/random/save','DonasiController@donasiRandomSave')->name('donasi.random.save');
    Route::get('donasi/mutasi/bank/index','DonasiController@mutasiBankIndex')->name('donasi.mutasi.bank.index');
    Route::post('donasi/mutasi/bank/detail','DonasiController@mutasiBankDetail')->name('donasi.mutasi.bank.detail');

    Route::get('report/donasi','ReportController@reportDonasi')->name('report.donasi');
    Route::get('report/donasi/harian','ReportController@reportDonasiHarian')->name('report.donasi.harian');
    Route::get('report/donasi/cicilan/outstanding','ReportController@reportOutStandingCicilan')->name('report.donasi.cicilan.outstanding');
    Route::get('report/donasi/cicilan/index','ReportController@reportDonaturDonasiIndex')->name('report.donasi.cicilan.index');
    Route::post('report/donasi/cicilan/donatur','ReportController@reportDonaturDonasiMain')->name('report.donasi.cicilan.donatur');
    Route::post('report/donasi/cicilan/main','ReportController@reportDonaturDonasiCicilanMain')->name('report.donasi.cicilan.main');
    Route::post('report/donasi/cicilan/cetak','ReportController@reportDonaturDonasiCicilanCetak')->name('report.donasi.cicilan.cetak');
    Route::get('report/santri/bimbingan','ReportController@reportSantriBimbingan')->name('report.santri.bimbingan');
    Route::get('report/santri/baru','ReportController@reportSantriBaru')->name('report.santri.baru');

    Route::get('kodepos/provinsi/all','KodePosController@kodeposProvinsiAll');
    Route::get('kodepos/kota/{provinsi}','KodePosController@kodeposKotaByProvinsi');
    Route::get('kodepos/kabupaten/{kota}','KodePosController@kecamatanByKota');
    Route::get('kodepos/kelurahan/{kabupaten}','KodePosController@kelurahanByKecamatan');

    Route::get('users/adm/index','UserController@admUserIndex')->name('users.adm.index');
    Route::post('users/adm/main','UserController@admUserMain')->name('users.adm.main');
    Route::put('users/adm/update/{id}','UserController@admUserResetPassword')->name('users.adm.update');
    
}); 
