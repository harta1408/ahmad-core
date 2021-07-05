<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pesan;

class PesanAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}

    #pesan dengsn status 1(unread) dan 2(read)
    public function pesanAktifByUserId($userid){
        $pesan=Pesan::with('tujuan')->where('pesan_tujuan_id',$userid)->get();
        return response()->json($pesan);  
    }
    public function pesanBelumTerbacaByUserId($userid){
        $pesan=Pesan::with('tujuan')->where([['pesan_tujuan_id',$userid],['pesan_status','1']])->get();
        return response()->json($pesan);  
    }
    public function pesanSudahTerbacaByUserId($userid){
        $pesan=Pesan::with('tujuan')->where([['pesan_tujuan_id',$userid],['pesan_status','2']])->get();
        return response()->json($pesan);  
    }
    public function pesanUpdateStatusTerbaca($id){ //id pesan
        //cari id user nya
        $userid=Pesan::where("id",$id)->first()->pesan_tujuan_id;
        //update status terbaca
        Pesan::where('id',$id)->update(['pesan_status'=>'2']);
        $pesan=Pesan::with('tujuan')->where('pesan_tujuan_id',$userid)->get();
        return response()->json($pesan);  
    }
    public function pesanUpdateStatusHapus($id){ //id pesan
        //cari id user nya
        $userid=Pesan::where("id",$id)->first()->pesan_tujuan_id;
        //update status terbaca
        Pesan::where('id',$id)->update(['pesan_status'=>'3']);
        $pesan=Pesan::with('tujuan')->where('pesan_tujuan_id',$userid)->get();
        return response()->json($pesan);  
    }
}
