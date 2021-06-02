<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pengingat;
use Validator;

class PengingatAPI extends Controller
{
    public function simpanPengingat(Request $request){
        $validator = Validator::make($request->all(), [
            'pengingat_isi' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $pengingat=new Pengingat;
        $pengingat->pengingat_isi=$request->get('pengingat_isi');
        $pengingat->pengingat_jenis=$request->get('pengingat_jenis');
        $pengingat->pengingat_lokasi_gambar=$request->get('pengingat_lokasi_gambar');
        $pengingat->pengingat_lokasi_video=$request->get('pengingat_lokasi_video');
        $pengingat->pengingat_status='1';//aktif
        $exec=$pengingat->save();

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }
        return response()->json($pengingat,200);  
    }
    public function pengingatUpdate($id,Request $request){
        $validator = Validator::make($request->all(), [
            'pengingat_isi' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $exec=Pengingat::where('id','=' ,$id)
        ->update(['pengingat_isi'=>$request->get('pengingat_isi'),
                  'pengingat_jenis'=>$request->get('pengingat_jenis'),
                  'pengingat_lokasi_gambar'=>$request->get('pengingat_lokasi_gambar'), 
                  'pengingat_lokasi_video'=>$request->get('pengingat_lokasi_video'), 
                  ]);
        $pengingat=Pengingat::where('id',$id)->first();
        return response()->json($pengingat,200);              
    }
    public function pengingatJenis($jenis){
        $pengingat=Pengingat::where([['pengingat_jenis',$jenis],['pengingat_status','1']])->get();
        return response()->json($pengingat,200);    
    }
    public function pengingatList(){
        $pengingat=Pengingat::where('pengingat_status','1')->get();
        return response()->json($pengingat,200); 
    }
}
