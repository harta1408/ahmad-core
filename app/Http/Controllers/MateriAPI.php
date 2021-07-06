<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Materi;

class MateriAPI extends Controller
{
    public function materiList(){
        $materi=Materi::where('materi_status','1')->get();
        return response()->json($materi,200);
    }
    public function materiByid($id){
        $materi=Materi::where('id',$id)->first();
        return response()->json($materi,200);
    }
}
