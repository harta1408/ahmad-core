<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Dashboard;
use App\Models\Bimbingan;
use App\Models\Santri;
use App\Models\Lembaga;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashHelpDeskIndex(){
        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $tanggal=$today->format('l d F o', Date::INDIAN_NUMBERS);
        $dashboard=$this->hitungDonasiHarian();

        // dd($tanggal);
        $todaydate=date("Y-m-d");
        $date = Hijri::convertToHijri($todaydate);


        
        $jumlahsantriotor=Santri::where('santri_status',3)->count();

        $dashboard=new Dashboard;
        $dashboard->dash_donasi_harian=$this->hitungDonasiHarian();
        $dashboard->dash_donasi_tagihan=$this->hitungOutstandingHarian();
        $dashboard->dash_bimbingan_jumlah=$this->hitungBimbingan();
        $dashboard->dash_donasi_santri_nambah=$this->hitungPenambahanSantri();
        $dashboard->dash_chart_donasi_status=$this->hitungChartDonasiStatus();
        $dashboard->dash_chart_santri_status=$this->hitungChartSantriStatus();
        return view('dashboard/helpdesk',compact('tanggal','dashboard'));
    }

    private function dashboardCard(){
        
        #hitung donasi yang gagal diterima
    }

    #hitung donasi yang diterima pada hari aktif
    private function hitungDonasiHarian(){
        $todaydate=date("Y-m-d");
        $nilai=Donasi::where('donasi_tanggal',$todaydate)->whereIn('donasi_status',['1','2','3'])
            ->sum('donasi_total_harga'); //status belum bayar dan sudah lunas harian
        return $nilai;
    }

    #hitung cicilan yang belum di bayar
    private function hitungOutstandingHarian(){
        $todaydate=date("Y-m-d");
        $nilai=DonasiCicilan::where([['cicilan_jatuh_tempo',$todaydate],['cicilan_status','1']])
            ->sum('cicilan_nominal'); //belum terbayar harian
        return $nilai;
    }

    #hitung jumlah bimbingan
    private function hitungBimbingan(){
        $nilai=Bimbingan::where('bimbingan_status','1')->count();
        return $nilai;
    }
    
    #hitung penambahan santri dari status 1,2 dan 3 (baru)
    private function hitungPenambahanSantri(){
        $nilai=Santri::whereIn('santri_status',['1','2','3'])->count();
        return $nilai;
    }

    private function hitungChartDonasiStatus(){
        $donasistatus=DB::table('donasi')->select('donasi_status',DB::RAW('COUNT(donasi_status) AS jumlah'))->groupBy('donasi_status')->get();
        foreach ($donasistatus as $key => $value) {
            if($value->donasi_status=='1'){
                $value->donasi_status='Belum Lunas';
            }
            if($value->donasi_status=='2'){
                $value->donasi_status='Pemilihan Santri';
            }
            if($value->donasi_status=='3'){
                $value->donasi_status='Bayar Tunai';
            }
            if($value->donasi_status=='4'){
                $value->donasi_status='Macet';
            }
            if($value->donasi_status=='5'){
                $value->donasi_status='Tersalurkan';
            }
        }
        return $donasistatus;
    }
    private function hitungChartSantriStatus(){
        $santristatus=DB::table('santri')->select('santri_status',DB::RAW('COUNT(santri_status) AS jumlah'))->groupBy('santri_status')->get();
        foreach ($santristatus as $key => $value) {
            if($value->santri_status=='1'){
                $value->santri_status='Data Belum Lengkap';
            }
            if($value->santri_status=='2'){
                $value->santri_status='Belum Isi Kuesioner';
            }
            if($value->santri_status=='3'){
                $value->santri_status='Menunggu Otorisasi';
            }
            if($value->santri_status=='4'){
                $value->santri_status='Menunggu Produk';
            }
            if($value->santri_status=='5'){
                $value->santri_status='Terpilih, Menunggu Produk';
            }
            if($value->santri_status=='6'){
                $value->santri_status='Dalam Bimbingan';
            }
            if($value->santri_status=='6'){
                $value->santri_status='Lulus';
            }
        }
        return $santristatus;
    }
}
