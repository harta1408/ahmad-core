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
    Route::get('user/reset/password/{email}','UserAPI@userResetPassword');
    Route::get('user/byid/{id}','UserAPI@userById');

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
    Route::get('donatur/santri/byid/{id}','DonaturAPI@donasiSantriById');
    Route::get('donatur/dashboard/{donaturid}','DonaturAPI@donaturDashboard');
    
    Route::post('santri/register','SantriAPI@santriRegister');
    Route::post('santri/register/referral','SantriAPI@santriRegisterReferral');
    Route::post('santri/register/gmail','SantriAPI@santriRegisterGMail');
    Route::put('santri/update/profile/{id}','SantriAPI@santriUpdateProfile');
    Route::post('santri/upload/photo','SantriAPI@santriUploadImage');
    Route::get('santri/byemail/{email}','SantriAPI@santriByEmail');
    Route::get('santri/list','SantriAPI@santriList');
    Route::get('santri/byid/{id}','SantriAPI@santriById');
    Route::get('santri/lacak/produk/{id}','SantriAPI@santriLacakProduk');
    Route::get('santri/dashboard/{id}','SantriAPI@santriDashboard');

    Route::post('pendamping/register','PendampingAPI@pendampingRegister');
    Route::post('pendamping/register/referral','PendampingAPI@pendampingRegisterReferral');
    Route::post('pendamping/register/gmail','PendampingAPI@pendampingRegisterGMail');
    Route::put('pendamping/update/profile/{id}','PendampingAPI@pendampingUpdateProfile');
    Route::get('pendamping/byid/{id}','PendampingAPI@pendampingById');
    Route::get('pendamping/santri/byid/{id}','PendampingAPI@pendampingSantriById');
    Route::post('pendamping/upload/photo','PendampingAPI@pendampingUploadImage');
    Route::post('pendamping/upload/cv','PendampingAPI@pendampingUploadCV');
    Route::get('pendamping/dashboard/{id}','PendampingAPI@pendampingDashboard');

    Route::post('produk/save','ProdukAPI@produkSimpan');
    Route::get('produk/byid/{id}','ProdukAPI@produkById');

    Route::post('rekening/save','RekeningAPI@rekeningSimpan');
    Route::put('rekening/update/{id}','RekeningAPI@rekeningUpdate');
    Route::get('rekening/byid/{id}','RekeningAPI@rekeningById');
    Route::get('rekening/list','RekeningAPI@rekeningList');
    
    Route::post('donasi/temp/save','DonasiAPI@donasiTempSimpan');
    Route::post('donasi/save','DonasiAPI@donasiSimpan');
    Route::get('donasi/byid/{id}','DonasiAPI@donasiById');
    Route::get('donasi/donasiid/{donasiid}/donaturid/{donaturid}','DonasiAPI@donasiDonaturById');
    Route::get('donasi/donatur/{id}','DonasiAPI@donasiByDonaturId');
    Route::get('donasi/outstanding/donatur/{donaturid}','DonasiAPI@donasiOutstandingByDonaturId');
    Route::get('donasi/outstanding/today/donatur/{donaturid}','DonasiAPI@donasiOutstandingTodayByDonaturId');
    Route::get('donasi/paid/donatur/{donaturid}','DonasiAPI@donasiPaidByDonaturId');
    Route::get('donasi/cicilan/donatur/{donaturid}','DonasiAPI@donasiCicilanByDonaturId');
    Route::get('donasi/santri/{id}','DonasiAPI@donasiSantriById');
    Route::put('donasi/update/rekening/{id}','DonasiAPI@donasiUpdateRekening');
    Route::post('donasi/periksa/bayar/donasi','DonasiAPI@donasiPeriksaPembayaran');

    Route::post('berita/save','BeritaAPI@beritaSimpan');
    Route::put('berita/update/{id}','BeritaAPI@beritaUpdate');
    Route::get('berita/entitas/{jenis}','BeritaAPI@beritaEntitas');
    Route::get('berita/list','BeritaAPI@beritaList');
    Route::get('berita/kampanye','BeritaAPI@beritaKampanye');
    Route::get('berita/kampanye/donatur/{nourut2digit}','BeritaAPI@beritaKampanyeDonatur');
    Route::get('berita/kampanye/santri/{nourut2digit}','BeritaAPI@beritaKampanyeSantri');
    Route::get('berita/kampanye/pendamping/{nourut2digit}','BeritaAPI@beritaKampanyePendamping');

    Route::get('hadist/entitas/donatur/{id}','HadistAPI@hadistByDonaturId');
    Route::get('hadist/entitas/santri/{id}','HadistAPI@hadistBySantriId');
    Route::get('hadist/entitas/pendamping/{id}','HadistAPI@hadistByPendampingId');
    Route::get('hadist/random/{jenis}','HadistAPI@hadistRandom');

    Route::post('referral/send/link','ReferralAPI@referralSendLink');
    Route::put('referral/update/berita','ReferralAPI@rreferralUpdateIdBerita');

    Route::get('pengingat/donatur/byid/{donaturid}','PengingatAPI@pengingatDonaturById');
    Route::put('pengingat/donatur/respon/{pengingatid}','PengingatAPI@pengingatDonaturRespon');
    Route::get('pengingat/santri/byid/{santriid}','PengingatAPI@pengingatSantriById');
    Route::put('pengingat/santri/respon/{pengingatid}','PengingatAPI@pengingatSantriRespon');
    Route::post('pengingat/bimbingan/simpan','PengingatAPI@pengingatSimpan');
    Route::put('pengingat/bimbingan/update/{id}','PengingatAPI@pengingatUpdate');
    Route::get('pengingat/pendamping/list/byid/{idpendamping}','PengingatAPI@pengingatListByPendampingId');

    Route::get('pesan/aktif/byuser/{userid}','PesanAPI@pesanAktifByUserId');
    Route::get('pesan/unread/byuser/{userid}','PesanAPI@pesanBelumTerbacaByUserId');
    Route::get('pesan/read/byuser/{userid}','PesanAPI@pesanSudahTerbacaByUserId');
    Route::get('pesan/update/read/{idpesan}','PesanAPI@pesanUpdateStatusTerbaca');
    Route::get('pesan/update/delete/{idpesan}','PesanAPI@pesanUpdateStatusHapus');

    Route::post('kuesioner/simpan','KuesionerAPI@kuesionerSimpan');
    Route::put('kuesioner/update/{id}','KuesionerAPI@kuesionerUpdate');
    Route::get('kuesioner/list','KuesionerAPI@kuesionerList');
    Route::get('kuesioner/entitas/{entitas}','KuesionerAPI@kuesionerByEntitas');
    Route::post('kuesioner/santri/simpan','KuesionerAPI@kuesionerSantriSimpan');
    Route::post('kuesioner/pendamping/simpan','KuesionerAPI@kuesionerPendampingSimpan');

    Route::post('message/send/wa','MessageAPI@sendWhatsApp');

    Route::post('bimbingan/penilaian/santri','BimbinganAPI@bimbinganPenilaian');
    Route::get('bimbingan/list/santri/donatur/{donaturid}','BimbinganAPI@bimbinganListSantryByDonatur');
    

    Route::get('materi/list','MateriAPI@materiList');
    Route::get('soal/list','SoalAPI@soalList');

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



