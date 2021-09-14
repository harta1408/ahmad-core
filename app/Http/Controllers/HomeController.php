<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;

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
        #untuk login ke dashbaord status approve harus 1
        $approve=Auth::user()->approve;
        $user = User::where('id','3')->with('roles')->get();
        // $role=$user->getRoleNames();
        // return $user;
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
