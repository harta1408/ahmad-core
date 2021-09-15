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
            // echo("ID ".$index."\n");
            foreach ($pengingat as $key => $pngt) {
                $pengingatid=$pngt->id;
                $index=$pngt->pengingat_index;
                $this->simpanPengingatBuatSantri($santriid,$pengingatid,$index);
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

        $seninkamisjumat=date('w');

        #ambil santri dengan status dalam bimbingan
        $santri=Santri::where('santri_status','6')->get();
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;
            #ambil index pengingat untuk pengingat jenis 4=senin dan 5=kamis 
            #kemudian update santri dengan status pengingat 0
            if($seninkamisjumat=='1' || $seninkamisjumat=='4'){
                $pengingat=Pengingat::whereIn('pengingat_jenis',['4','5'])->get();
                foreach ($pengingat as $key => $value) {
                    $pengingatindex=$value->pengingat_index;
                    $pengingatid=$value->id;                    
                    $status=DB::table('pengingat_santri')->select('pengingat_santri_status')
                        ->where([['pengingat_id',$pengingatid],['santri_id',$santriid],['pengingat_santri_index',$pengingatindex]])
                        ->first()->pengingat_santri_status;
                    if($status=='0'){
                        echo($status."\n");
                        $pengingat=Pengingat::where('id',$pengingatid)->first();
                        $pengingat->santri()->updateExistingPivot($santriid, [
                            'pengingat_santri_status'=>'1',
                        ]);
                        #jika ketemu langsung keluar dari loop pengingat
                        break;
                    }

                }
            }
            #ambil index pengingat untuk pengingat jenis 6=jumat
            if($seninkamisjumat=='5'){
                $pengingat=Pengingat::where('pengingat_jenis','6')->get();
                foreach ($pengingat as $key => $value) {
                    $pengingatindex=$value->pengingat_index;
                    $pengingatid=$value->id;
                    $status=DB::table('pengingat_santri')->select('pengingat_santri_status')
                        ->where([['pengingat_id',$pengingatid],['santri_id',$santriid],['pengingat_santri_index',$pengingatindex]])
                        ->first()->pengingat_santri_status;
                    if($status=='0'){
                        echo($status."\n");
                        $pengingat=Pengingat::where('id',$pengingatid)->first();
                        $pengingat->santri()->updateExistingPivot($santriid, [
                            'pengingat_santri_status'=>'1',
                        ]);
                        #jika ketemu langsung keluar dari loop pengingat
                        break;
                    }

                }
            }
        }
    }
 
    public function pengingatSembunyikan(){
        #menyembunyikan pengingat yang telah lewat hari
        #cari pengingat santri berdasarkan tanggal kemarin
        $kemarin=date('Y-m-d',strtotime("yesterday"));
        // $kemarin=date('Y-m-d'); untuk test code

        $kemarinawal=$kemarin.' 00:00:00';
        $kemarinakhir=$kemarin.' 23:59:59';

        #ambil santri dengan status dalam bimbingan
        $santri=Santri::where('santri_status','6')->get();
        foreach ($santri as $key => $sntr) {
            $santriid=$sntr->id;
            $pengingatsantri=DB::table('pengingat_santri')->select('pengingat_id','pengingat_santri_status')
                ->where([['santri_id',$santriid],['pengingat_santri_status','1']])
                ->whereBetween('updated_at',[$kemarinawal,$kemarinakhir])
                ->first();
            if(!$pengingatsantri){
                continue;
            }
            $pengingatid=$pengingatsantri->pengingat_id;
            $status=$pengingatsantri->pengingat_santri_status;

            echo($status."\n");
            $pengingat=Pengingat::where('id',$pengingatid)->first();
            $pengingat->santri()->updateExistingPivot($santriid, [
                'pengingat_santri_status'=>'2',
            ]);
        }
    }
}
