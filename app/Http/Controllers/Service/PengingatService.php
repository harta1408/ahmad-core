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
use DB;


class PengingatService extends Controller
{
    public function pengingatDonasi(){
        #mengirimkan pengingat sesuai dengan pilihan pembayaran
        #1 Harian (setiap subuh) 2. Pekanan (setiap jumat)  3. Bulanan (yaumil bidh)

        $todaydate=date("Y-m-d").' 00:00:00';
        $donasicicilan=DonasiCicilan::with('donasi.donatur')
            ->where([['cicilan_jatuh_tempo','=',$todaydate],['cicilan_status','1']])->get();
        

        foreach ($donasicicilan as $key => $dc) {
            $donaturid=$dc->donasi->donatur->id;
            $carabayar=$dc->donasi->donasi_cara_bayar;
            if($carabayar!='4'){ //harian, pekanan dan bulanan
                #ambil pengingat harian dengan entitas donatur
                $pengingat=Pengingat::where([['pengingat_jenis',$carabayar],['pengingat_entitas','1']])->first();
                $pengingatid=$pengingat->id;
                #update status jika ada pengingat sebelumnya yang masih aktif
                $donatur = Donatur::find($donaturid);
                $donatur->pengingat()->updateExistingPivot($pengingatid, [
                    'pengingat_donatur_status' => '0',
                ]);

                $pengingat->donatur()->attach(['pengingat_id'=>$pengingatid],
                [
                    'donatur_id'=>$donaturid, 
                    'pengingat_donatur_respon'=>'0', 
                    'pengingat_donatur_status'=>'1', //aktif
                ]);
            }
        }
    }
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
    public function pesanJatuhTempo(){
        #mengirimpan pesan jatuh tempo
        $todaydate=date("Y-m-d").' 00:00:00';
        $donasicicilan=DonasiCicilan::with('donasi.donatur')
            ->where([['cicilan_jatuh_tempo','<',$todaydate],['cicilan_status','1']])->get();

        foreach ($donasicicilan as $key => $dc) {
            $donaturid=$dc->donasi->donatur->id;
            $donasino=$dc->donasi->donasi_no;
            $cicilanke=$dc->cicilan_ke;
            $jatuhtempo=date('d-m-Y',strtotime($dc->cicilan_jatuh_tempo));
            
            
            #kirikan pesan
            $msg=new MessageService;
            $pengirim='0'; //dari sistem
            
            $emaildonatur=Donatur::where('id',$donaturid)->first()->donatur_email;
            $tujuan=User::where('email',$emaildonatur)->first()->id;
            $isi='Anda memiliki Donasi No '.$donasino.' Cicilan ke-'.$cicilanke.' yang jatuh tempo pada tanggal '.$jatuhtempo
                .', abaikan pesan ini jika telah melakukan pembayaran';
            $msg->saveNotification($pengirim,$tujuan,$isi);
        }
    }
}
