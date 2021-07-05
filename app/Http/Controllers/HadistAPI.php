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
}
