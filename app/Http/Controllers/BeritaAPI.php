<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Berita;
use Validator;

class BeritaAPI extends Controller
{
    public function beritaSimpan(Request $request){
        $validator = Validator::make($request->all(), [
            'berita_isi' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $berita=new Berita;
        $berita->berita_isi=$request->get('berita_isi');
        $berita->berita_jenis=$request->get('berita_jenis');
        $berita->berita_entitas=$request->get('berita_entitas');
        $berita->berita_lokasi_gambar=$request->get('berita_lokasi_gambar');
        $berita->berita_lokasi_video=$request->get('berita_lokasi_video');
        $berita->berita_status='1';//aktif
        $exec=$berita->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }
        return response()->json($berita,200);  
    }
    public function beritaUpdate($id,Request $request){
        $validator = Validator::make($request->all(), [
            'berita_isi' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Berita::where('id','=' ,$id)
        ->update(['berita_isi'=>$request->get('berita_isi'),
                  'berita_jenis'=$request->get('berita_jenis'),
                  'berita_entitas'=>$request->get('berita_entitas'),
                  'berita_lokasi_gambar'=>$request->get('berita_lokasi_gambar'), 
                  'berita_lokasi_video'=>$request->get('berita_lokasi_video'), 
                  ]);
        $berita=Berita::where('id',$id)->first();
        return response()->json($berita,200);              
    }
    public function beritaEntitas($entitas){
        $berita=Berita::where([['berita_entitas',$entitas],['berita_status','1']])->get();
        return response()->json($berita,200);    
    }
    public function beritaList(){
        $berita=Berita::where('berita_status','1')->get();
        return response()->json($berita,200); 
    }
}
