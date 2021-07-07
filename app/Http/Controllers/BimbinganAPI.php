<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bimbingan;
use App\Models\Materi;
use App\Models\BimbinganMateri;
use Validator;

class BimbinganAPI extends Controller
{
    public function bimbinganPenilaian(Request $request){
        #satu bimbingan aktif pasti satu santri

        #validasi
        $validator = Validator::make($request->all(), [
            'santri_id' => 'required|string',
            'materi_id' => ['required','string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $santriid=$request->get('santri_id');
        $materiid=$request->get('materi_id');
        $pendampingid=$request->get('pendamping_id');

        $bimbingan=Bimbingan::where('santri_id',$santriid)->first();
        $bimbinganid=$bimbingan->id;
        #pastikan tidak ada materi bimbingan yang ganda
        $bimbingan->materi()->detach($materiid);
        $bimbingan->materi()->attach(['materi_id'=>$materiid],
            [
                'bimbingan_materi_angka'=>$request->get('bimbingan_materi_angka'), 
                'bimbingan_materi_huruf'=>$request->get('bimbingan_materi_huruf'),
                'bimbingan_materi_catatan' =>$request->get('bimbingan_materi_catatan'),
            ]);

        $bimbingan=Bimbingan::with('materi')->where('id',$bimbinganid)->first();
        return response()->json($bimbingan,200);

    }

    public function bimbinganDashboardSantri($santriid){
        $jmlmateri=Materi::where('materi_status','1')->count();
        $bimbingan=Bimbingan::where('santri_id',$santriid)->first();
        $bimbinganid=$bimbingan->id;

        $todaydate=date_create(date("Y-m-d"));
        $bimbinganmulai=date_create($bimbingan->bimbingan_mulai);
        $bimbinganakhir=date_create($bimbingan->bimbingan_berakhir);

        $jangkawaktu=date_diff($bimbinganmulai,$bimbinganakhir)->days;;
        $berjalan=date_diff($todaydate,$bimbinganakhir)->days;
        $sisabulan=date_diff($todaydate,$bimbinganakhir)->m.' bulan';
        
        $materiselesai=BimbinganMateri::where('bimbingan_id',$bimbinganid)->count();

        $progresbelajar=$materiselesai/$jmlmateri;
        $waktubelajar=1-($berjalan/$jangkawaktu);

        $dashsantri=['santri_id'=> $santriid,
                     'bimbingan_hari_ini' => date("Y-m-d"),
                     'bimbingan_mulai' => $bimbingan->bimbingan_mulai,
                     'bimbingan_akhir' => $bimbingan->bimbingan_berakhir,
                     'santri_progress_belajar'=>$progresbelajar,
                     'santri_progress_waktu'=>$waktubelajar,
                     'santri_sisa_bulan'=>$sisabulan];

        return response()->json($dashsantri,200);
    }
}
