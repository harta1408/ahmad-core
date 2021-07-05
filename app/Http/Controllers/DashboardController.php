<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Dashboard;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;

class DashboardController extends Controller
{
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

    private function hitungDonasiHarian(){
        $nilai=Donasi::whereIn('donasi_status',['2','3'])->sum('donasi_total_harga');
        $jumlah=Donasi::where('donasi_status','2')->sum('donasi_jumlah_santri');
        $dashboard=new Dashboard;
        $dashboard->dash_donasi_nilai=$nilai;
        $dashboard->dash_donasi_jumlah=$jumlah;
        return $dashboard;
    }
}
