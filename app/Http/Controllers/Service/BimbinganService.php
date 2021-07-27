<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DonasiCicilan;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pengingat;
use App\Models\User;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\BayarService;
use DB;

class BimbinganService extends Controller
{
  
    public function pengingatBimbingan(){
        #mengirimkan pengingat bimbingan denan ketentuan 
        #senin atau kamis berupa konten video dan jumat untuk konten image
        #dikirimkan sebanyak setiap bulan (10 kali) selama masa bimbingan
        $seninkamis=date('w');

        #periksan santri dengan status dalam bimbingan
        $santri=Santri::where('santri_status','6')->get();
        
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;

            if($seninkamis=='1' || $seninkamis=='4'){
                #ambil pengingat senin (4) atau kamis (5) dengan entitas santri
                $pengingat=Pengingat::where([['pengingat_jenis',$seninkamis],['pengingat_entitas','2']])->first();
                $pengingatid=$pengingat->id;

                #hitung index
                $index=DB::table('pengingat_santri')->select('pengingat_santri_index')->where([['pengingat_id',$pengingatid],['santri_id',$santriid]])->max('pengingat_santri_index');
                if(!$index){
                    $index=1;
                }else{
                    $index=$index+1;
                }
                #update status jika ada pengingat sebelumnya yang masih aktif
                $santri = Santri::find($santriid);
                $santri->pengingat()->updateExistingPivot($pengingatid, [
                    'pengingat_santri_status' => '0',
                ]);
                #simpan pengingat pada santri  
                $pengingat->santri()->attach(['pengingat_id'=>$pengingatid],
                [
                    'santri_id'=>$santriid, 
                    'pengingat_santri_index'=>$index,
                    'pengingat_santri_respon'=>'0',
                    'pengingat_santri_status'=>'1', //aktif
                ]);   
            }
        }
    }
 
}
