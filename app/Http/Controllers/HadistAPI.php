<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Hadist;

class HadistAPI extends Controller
{
    public function hadistByDonaturId($donaturid){
        $donatur=function ($query) use ($donaturid){
            $query->where('id',$donaturid);
        };
        $hadist=Hadist::with(['donatur'=> $donatur])->whereHas('donatur',$donatur)->first();
        return response()->json($hadist,200); 
    }
    public function hadistBySantriId($santriid){
        $santri=function ($query) use ($santriid){
            $query->where('id',$santriid);
        };
        $hadist=Hadist::with(['santri'=> $santri])->whereHas('santri',$santri)->first();
        return response()->json($hadist,200); 
    }
    public function hadistByPendampingId($pendampingid){
        $pendamping=function ($query) use ($pendampingid){
            $query->where('id',$pendampingid);
        };
        $hadist=Hadist::with(['pendamping'=> $pendamping])->whereHas('pendamping',$pendamping)->first();
        return response()->json($hadist,200); 
    }
    public function hadistRandom($jenis){
        $jmlhadist=Hadist::where([['hadist_status','1'],['hadist_jenis',$jenis]])->count();
        if($jmlhadist==0){
            return response()->json(['status' => 'error', 'message' => 'Belum ada data Hadist atau Doa', 'code' => 404]);
        }
        if($jmlhadist==1){
            $hadist=Hadist::where([['hadist_status','1'],['hadist_jenis',$jenis]])->first();
            return response()->json($hadist,200);
        }
        $hadist=Hadist::where([['hadist_status','1'],['hadist_jenis',$jenis]])->pluck('id')->toArray();
        $randomhadist=array_rand($hadist,$jmlhadist);
        $hadist=Santri::where('id',$hadist[$randomhadist])->first();
        return response()->json($hadist,200);
    }

}
