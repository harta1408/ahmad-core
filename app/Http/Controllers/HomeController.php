<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $approve=Auth::user()->approve;
        if($approve=='1' ){ //approved
            // return redirect()->action('HomeController@saDashboardIndex'); 
            return redirect()->action('DashboardController@dashHelpDeskIndex');
            // return view('layouts.menus');
        }else{
            return view('home');
        }

        // if(Entrust::hasRole("superadmin")){
            // return view('home');
            // 
        // }

    }
}
