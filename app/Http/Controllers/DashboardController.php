<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Dashboard;
use App\Models\Bimbingan;
use App\Models\Santri;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashHelpDeskIndex(){
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $tanggal=$today->format('l d F o', Date::INDIAN_NUMBERS);
        $dashboard=$this->hitungDonasiHarian();

        // dd($tanggal);
        $todaydate=date("Y-m-d");
        $date = Hijri::convertToHijri($todaydate);

        return view('dashboard/helpdesk',compact('tanggal','dashboard'));
    }

    private function dashboardCard(){
        #hitung donasi yang diterima pada hari aktif

        #hitung donasi yang seharusnya diterima

        #hitung donasi yang gagal diterima
    }

    private function hitungDonasiHarian(){
        $nilai=Donasi::whereIn('donasi_status',['2','3'])->sum('donasi_total_harga'); //status sudah di bayar dan disalurkan
        $jumlah=Donasi::where('donasi_status','2')->sum('donasi_jumlah_santri');
        $jumlahbimbingan=Bimbingan::where('bimbingan_status','1')->count();
        $jumlahsantriotor=Santri::where('santri_status',3)->count();


        $dashboard=new Dashboard;
        $dashboard->dash_donasi_nilai=$nilai;
        $dashboard->dash_donasi_jumlah=$jumlah;
        $dashboard->dash_bimbingan_jumlah=$jumlahbimbingan;
        $dashboard->dash_santri_otorisasi=$jumlahsantriotor;
        return $dashboard;
    }
}
