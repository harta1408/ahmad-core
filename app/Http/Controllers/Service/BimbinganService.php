<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DonasiCicilan;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pengingat;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pendamping;
use App\Models\DonaturSantri;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\BayarService;
use DB;

class BimbinganService extends Controller
{
    public function bimbinganSantriMulai($santriid,$mulai){
        #proses bimbingan katika produk sampai di santri
        $produkid=Bimbingan::where('santri_id',$santriid)->first()->produk_id;
        $dayno=Produk::where('id',$produkid)->first()->produk_masa_bimbingan;
        $santri=Santri::where('id',$santriid)->first();

        #hitung penambahan tanggal untuk menentukan tanggal akhir
        $akhir=date('Y-m-d',strtotime($mulai.' '.$dayno." days"));

        #pebaharui data
        Bimbingan::where('santri_id',$santriid)->update([
            'bimbingan_mulai'=>$mulai,
            'bimbingan_berakhir' =>$akhir,
            'bimbingan_status'=>'1']);
        $pendampingid=Bimbingan::where('santri_id',$santriid)->first()->pendamping_id;
        $donaturid=DonaturSantri::where([['santri_id',$santriid],['pendamping_id',$pendampingid]])->first()->donatur_id;
        $donaturemail=Donatur::where('id',$donaturid)->first()->donatur_email;
        $santriemail=$santri->santri_email;
        $pendampingemail=Pendamping::where('id',$pendampingid)->first()->pendamping_email;

        #update status santri dan pendamping dalam bimbingan
        Santri::where('id',$santriid)->update(['santri_status'=>'6']);
        Pendamping::where('id',$pendampingid)->update(['pendamping_status'=>'5']);

        #kirim pesan ke donatur, santri dan pendamping bahwa produk telah diterima (belum aktif)
        $msg=new MessageService;
        $pengirim='0'; //dari sistem
        $santrinama=$santri->santri_nama;

        #pesan untuk donatur
        $tujuan=User::where('email',$donaturemail)->first()->id;
        $isi='Bimbingan santri atas nama '.$santrinama.' telah dimulai';
        $msg->saveNotification($pengirim,$tujuan,$isi);

        #pesan untuk santri
        $tujuan=User::where('email',$santriemail)->first()->id;
        $isi='Produk sudah diterima, silakan untuk memulai bimbingan' ;
        $msg->saveNotification($pengirim,$tujuan,$isi);

        #pesan untuk pendamping
        $tujuan=User::where('email',$pendampingemail)->first()->id;
        $isi='Bimbingan santri atas nama '.$santrinama.' telah dimulai, silakan untuk ditindaklanjuti';
        $msg->saveNotification($pengirim,$tujuan,$isi);
    }

    public function pengingatBuatSantri(){
        #membuat pengingat untuk santri dalam bimbingan, sejumlah pengingat bimbingan yang di buat
        #proses dilakukan setiap hari, jika ada materi baru langsung di tambahkan kedalam daftar
        #4=Senin 5=Kamis 6=Jumat 7=online meeting 8=offline meeting 9=talkin dzikir

        $santri=Santri::where('santri_status','6')->get();
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;
            $pengingat=Pengingat::whereIn('pengingat_jenis',['4','5','6'])->get();
            $index=1;
            // echo("ID ".$index."\n");
            foreach ($pengingat as $key => $pngt) {
                $pengingatid=$pngt->id;
                $this->simpanPengingatBuatSantri($santriid,$pengingatid,$index);
                $index++;
            }
        }
    }
    private function simpanPengingatBuatSantri($santriid,$pengingatid,$index){
        #periksa paka pengingat sudah ada di dalam tabel, jika sudah terisi maka
        #tidak perlu ditambahkan
        $terisi=DB::table('pengingat_santri')->select('pengingat_id')->where([['pengingat_id',$pengingatid],['santri_id',$santriid]])->count('pengingat_id');
        if($terisi!=0){
            echo("ID Sudah ada \n");
            return;
        }
        echo("ID ".$pengingatid."\n");

        #jika belum ada simpan pada tabel relasi
        $pengingat=Pengingat::where('id',$pengingatid)->first();
        $pengingat->santri()->attach(['pengingat_id'=>$pengingatid],
        [
            'santri_id'=>$santriid, 
            'pengingat_santri_index'=>$index,
            'pengingat_santri_respon'=>'0',
            'pengingat_santri_status'=>'1', //aktif
        ]);   
    }
    public function pengingatBimbingan(){
        #menampilkan pengingat untuk santri sesuai dengan waktu dan materi yang harus di dapatkan
        #senin atau kamis berupa konten video dan jumat untuk konten image
        #dikirimkan sebanyak setiap bulan (10 kali) selama masa bimbingan


        #update status sebelumnya menjadi 2=sudah selesai


        // $kemarin=date("Y-m-d",strtotime("yesterday"));
        // $index=DB::table('pengingat_santri')->select('pengingat_santri_index')->where([['pengingat_id',$pengingatid],['santri_id',$santriid]])->max('pengingat_santri_index');

        // echo($kemarin."\n");
        // return;

        $seninkamisjumat=date('w');

        #ambil santri dengan status dalam bimbingan
        $santri=Santri::where('santri_status','6')->get();
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;
            #ambil index pengingat santri terkecil dengan status 0=tidak aktif
            $pengingatid=DB::table('pengingat_santri')
                ->join('pengingat','pengingat_santri.pengingat_id','=','pengingat.id')
                ->select('pengingat_id','pengingat_santri_index')
                ->where([['pengingat_santri_status','0'],['santri_id',$santriid]])
                ->wherein('pengingat.pengingat_status',['4','5','6'])
                ->orderby('pengingat_santri_index','asc')->first();

            echo(json_encode($pengingatid)."\n");
            // return;

            // #update status menjadi 1
            // DB::table('pengingat_santri')
            //     ->where([['pengingat_id',$pengingatid],['santri_id',$santriid]])
            //     ->update(['pengingat_santri_status'=>'1']);


       


        }
return;
        
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;

            if($seninkamis=='1' || $seninkamis=='4'){
                #ambil pengingat senin (4) atau kamis (5) dengan entitas santri
                #dengan status yang belum aktif, untuk di ubah menhadi aktif
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
                    'pengingat_santri_status'=>'0', //aktif
                ]);   
            }
        }
    }
 
}
