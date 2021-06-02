<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Rekening;
use Validator;

class RekeningAPI extends Controller
{
    public function rekeningSimpan(Request $request){
        $validator = Validator::make($request->all(), [
            'rekening_no' => ['required','string','max:20'],
            'rekening_nama' => ['required','string','max:30'],
            'rekening_nama_bank'=>'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $rekening=new Rekening();
        $rekening->rekening_nama=$request->get('rekening_nama');
        $rekening->rekening_no=$request->get('rekening_no');  
        $rekening->rekening_nama_bank=$request->get('rekening_nama_bank');
        $rekening->rekening_status='1'; //rekening aktif
        $rekening->save();
        return response()->json($rekening,200);

    }

    public function rekeningUpdate($id,Request $request){
        $exec=Rekening::where('id','=' ,$id)
        ->update(['rekening_nama'=>$request->get('rekening_nama'),
                  'rekening_no'=>$request->get('rekening_no'),
                  'rekening_nama_bank'=>$request->get('rekening_nama_bank'), 
                  ]);
        $rekening=Rekening::where('id',$id)->first();
        return response()->json($rekening,200);

    }

    public function rekeningById($id){
        $rekening=Rekening::where([['rekening_status','1'],['id',$id]])->first();
        return response()->json($rekening,200);
    }
    public function rekeningList(){
        $rekening=Rekening::where('rekening_status','1')->get();
        return response()->json($rekening,200);
    }
}
