<?php

namespace App\Http\Controllers\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bayar;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use App\Models\Donatur;
use App\Models\Pengingat;
use App\Models\User;
use App\Models\RekeningBank;
use App\Http\Controllers\Service\MessageService;
use PDF;

class DonasiService extends Controller
{
    public function bayarPeriksaHarian(Request $request){
        #periksan cicilan yang sesuai dengan tanggal aktif
        #jika sesuai lakukan pemeriksaan kedalam rekening
        #jika ditemukan update status pembayaran cicilan
    }

    public function bayarCicilan($cicilanid,$tglbayar){
        #ambil donasi id dari tabel cicilan
        $donasicicilan=DonasiCicilan::where('id',$cicilanid)->first();
        $donasiid=$donasicicilan->donasi_id;
        $cicilanke=$donasicicilan->cicilan_ke;
        
        #jika cicilan pertama maka update bayar, jika cicilan berikutnya maka 
        #isi tabel bayar dengan status telah terbauar
        Bayar::where('cicilan_id',$cicilanid)->update(['bayar_tanggal'=>$tglbayar,'bayar_status'=>'2']);

        $msg=new MessageService;
        $pengirim='0'; //dari sistem

        #update status donasi, jika cara bayar 4 berarti pembayaran langsung pelunasan
        $donasi=Donasi::with('cicilan')->where('id',$donasiid)->first();
        $donasino=$donasi->donasi_no;
        $donaturid=$donasi->donatur_id;
        if($donasi->donasi_cara_bayar=='4'){ //pembayaran tunai
            Donasi::where('id',$donasiid)->update(['donasi_status'=>'3']);

            #kirikan pesan
            $donatur=Donatur::where('id',$donaturid)->first();
            $emaildonatur=$donatur->donatur_email;
            $namadonatur=$donatur->donatur_nama;
            $tujuan=User::where('email',$emaildonatur)->first()->id;
            $isi='Terimakasih, Pembayaran Tunai Donasi No '.$donasino.' telah diterima';
            $msg->saveNotification($pengirim,$tujuan,$isi);

            #kirimkan email
            $msg->kirimEmailInvoice($emaildonatur,$namadonatur,$cicilanid);
        }else{
            #jika status selainya di hitung, apakah cicilan yang lunas sudah mencapai lebih dari 200rb
            #jika sudah update status donasi menajadi elogile for random (2)
            $cicilan=$donasi->cicilan;
            $totalbayar=0;
            foreach ($cicilan as $key => $cicil) {
                if($cicil->cicilan_status=='2'){
                    $totalbayar=$totalbayar+$cicil->cicilan_nominal;
                }
            }
            if($totalbayar>=200000){
                //update status donasi menjadi bisa random santri
                Donasi::where('id',$donasiid)->update(['donasi_status'=>'2']);
            }

            #kirikan pesan
            $donatur=Donatur::where('id',$donaturid)->first();
            $emaildonatur=$donatur->donatur_email;
            $namadonatur=$donatur->donatur_nama;
            $tujuan=User::where('email',$emaildonatur)->first()->id;
            $isi='Terimakasih, Pembayaran cicilan ke-'.$cicilanke.', Donasi No '.$donasino.' telah diterima';
            $msg->saveNotification($pengirim,$tujuan,$isi);

            #kirimkan email
            $msg->kirimEmailInvoice($emaildonatur,$namadonatur,$cicilanid);
        }
    }

    #modul untuk membuat daftar tagihan pembayaran harian, dengan status pembayaran 1 (belum bayar)
    public function bayarBuatTagihanHarian($cicilanid){
        #cari pada tabel bayar apakah pembayaran untuk cicilan id tersebut sudah di buat atau belum
        $bayar=Bayar::where('cicilan_id',$cicilanid)->first();
        if($bayar){
            //jika sudah ada tidak perlu dibuat lagi
            return;
        }

        $cicilan=DonasiCicilan::where('id',$cicilanid)->first();
        $donasiid=$cicilan->donasi_id;
        $cicilanke=$cicilan->cicilan_ke;
        $donasi=Donasi::where('id',$donasiid)->first();
        $donasino=$donasi->donasi_no;
        $rekeningid=$donasi->rekening_id;
        $donaturid=$donasi->donatur_id;
        $jatuhtempo=date('d-m-Y',strtotime($cicilan->cicilan_jatuh_tempo));

        #ambil nomor rekening
        $rekening=RekeningBank::where('id',$rekeningid)->first();
        $rekeningno=$rekening->rekening_no;
        $rekeningnama=$rekening->rekening_nama;
        $rekeningnamabank=$rekening->rekening_nama_bank;

        //simpan pembayaran dengan status belum dibayar, pembayaran akan berubah status menjadi 
        //sudah di bayar ketika melakukan pengecekan ke rekening bank
        $kodeunik=rand(0,100);
        $bayartotal=$cicilan->cicilan_nominal+$kodeunik;
        $bayar=new Bayar;
        $bayar->cicilan_id=$cicilan->id;
        $bayar->bayar_total=$bayartotal;
        $bayar->bayar_kode_unik=$kodeunik;
        $bayar->bayar_disc=0;
        $bayar->bayar_onkir=0;
        $bayar->bayar_status=1;
        $bayar->save();

        #kirikan pesan pengingat
        $msg=new MessageService;
        $pengirim='0'; //dari sistem
        
        $emaildonatur=Donatur::where('id',$donaturid)->first()->donatur_email;
        $tujuan=User::where('email',$emaildonatur)->first()->id;
        $isi='Silakan bayar Rp. '.$bayartotal.' untuk Donasi No '.$donasino
                .' cicilan ke-'.$cicilanke.' melalui '.$rekeningnamabank
                .' no '.$rekeningno.' an: '.$rekeningnama
                .' jatuh tempo '.$jatuhtempo
                .', abaikan pesan ini jika telah membayar';
        $msg->saveNotification($pengirim,$tujuan,$isi);
        return $bayartotal;
    }

    public function pengingatDonasi(){
        #mengirimkan pengingat sesuai dengan pilihan pembayaran
        #1 Harian (setiap subuh) 2. Pekanan (setiap jumat)  3. Bulanan (yaumil bidh)
        $todaydate=date("Y-m-d").' 00:00:00';
        $donasicicilan=DonasiCicilan::with('donasi.donatur')
            ->where([['cicilan_jatuh_tempo','=',$todaydate],['cicilan_status','1']])->get();

        foreach ($donasicicilan as $key => $dc) {
            $cicilanid=$dc->id;
            $donaturid=$dc->donasi->donatur->id;
            $carabayar=$dc->donasi->donasi_cara_bayar;
            
            if($carabayar!='4'){ //harian, pekanan dan bulanan
                #jika belum ada, buat tabel pembayaran untuk ciclan tsb
                $this->bayarBuatTagihanHarian($cicilanid);
            
                #ambil pengingat harian dengan entitas donatur
                $pengingat=Pengingat::where([['pengingat_jenis',$carabayar],['pengingat_entitas','1']])->first();
                if(!$pengingat){
                    echo("Tabel Pengingat dengan ketentuan donasi yang diminta tidak ditemukan\n");
                    return;
                }
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

    public function randomSantri(){
        #proses ini dilakukan setiap hari dengan memeriksa apakah ada donasi yang sudah mencapai >=200rb
        #atau dengan memeriksan flag pada tabel donasi, donasi_status=2 atau donasi TUNAI (cara bayar 4)
        #dengan flag donasi_random_santri=0
        #setelah itu dilakukan pemeriksanaan juga pada tabel donatur_santri, apakah donasi telah disalurkan
        
    }
    public function donasiCicilanPDF($id){
        $donasi=Donasi::with('donatur','rekeningbank','cicilan')->where('id',$id)->first();
        $donasino=$donasi->donasi_no;
        $tanggalakhir=DonasiCicilan::where('donasi_id',$id)->orderBy('cicilan_jatuh_tempo','desc')->first()->cicilan_jatuh_tempo;
        $donasi->donasi_tanggal_akhir=$tanggalakhir;
      
            
        $pdf = PDF::loadView('email/pdfcicilan', compact('donasi'))
                ->setPaper([0, 0, 650, 900], 'potrait'); //dalam point unit(bukan mm)
                // ->setPaper([0, 0, 209, $tinggi], 'potrait'); //dalam point unit(bukan mm)
        $filename=public_path().'/images/donasi_'.$donasino.'.pdf';
        return $pdf->save($filename); //output web
    } 
    public function donasiInvoicePDF($idcicilan){
        $donasicicilan=DonasiCicilan::with('donasi.donatur','bayar')->where('id',$idcicilan)->first();
        $donasino=$donasicicilan->donasi->donasi_no;
        $cicilanke=$donasicicilan->cicilan_ke;
            
        $pdf = PDF::loadView('email/pdfinvoice', compact('donasicicilan'))
                ->setPaper([0, 0, 650, 900], 'potrait'); //dalam point unit(bukan mm)
                // ->setPaper([0, 0, 209, $tinggi], 'potrait'); //dalam point unit(bukan mm)
        $filename=public_path().'/images/invoice_'.$donasino.'-'.$cicilanke.'.pdf';
        return $pdf->save($filename); //output web       
    }
}
