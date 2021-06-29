<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;

class DashboardController extends Controller
{
    public function dashHelpDeskIndex(){
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $tanggal=$today->format('l d F o', Date::INDIAN_NUMBERS);


        // dd($tanggal);
        $todaydate=date("Y-m-d");
        $date = Hijri::convertToHijri($todaydate);

        return view('dashboard/helpdesk',compact('tanggal'));
    }
}
