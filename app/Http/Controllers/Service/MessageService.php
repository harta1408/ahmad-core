<?php

namespace App\Http\Controllers\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\Pesan;
use App\Models\User;
use App\Models\Donasi;
use App\Models\DonasiCicilan;
use Config;
use PDF;

class MessageService extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function kirimEmailVerifikasi($useremail,$username,$hashcode){

        // return; //sementara program dibuat agar tidak mengirim email

        // kirim email registrasi
        $tipe=User::where('email',$useremail)->first()->tipe;
        $url="";
        if($tipe=='1'){
            $url=Config::get('ahmad.register.production.donatur');
        }
        if($tipe=='2'){
            $url=Config::get('ahmad.register.production.santri');
        }
        if($tipe=='3'){
            $url=Config::get('ahmad.register.production.pendamping');
        }
        $url=$url.$hashcode;
        $data = array('name'=>$username,'url'=>$url);
        Mail::send('email/register', $data, function($message) use($useremail, $username) {
           $message->to($useremail, $username)->subject
              ('no-reply : Pendaftaran Gerakan Ahsoha');
           $message->from('noreply@ahsoha.org','Gerakan Ahsoha');
        });
        // echo "HTML Email Sent. Check your inbox.";
        return;
    }
    public function kirimEmailRegistrasiPendamping($useremail,$username){

        // return; //sementara program dibuat agar tidak mengirim email

        // kirim email registrasi
        $tipe=User::where('email',$useremail)->first()->tipe;
        $url="https://ahsoha.id/public/dashboard/pendamping/pembaharuan/index";

        $data = array('nama'=>$username,'url'=>$url);
        Mail::send('email/registerpendamping', $data, function($message) use($useremail, $username) {
           $message->to($useremail, $username)->subject
              ('no-reply : Pengajuan Pendamping Gerakan Ahsoha');
           $message->from('noreply@ahsoha.id','Gerakan Ahsoha');
        });
        // echo "HTML Email Sent. Check your inbox.";
        return;
    }    

    public function kirimEmailResetPassword($username,$useremail,$newpass){
        $data = array('name'=>$username,'email'=>$useremail,'newpass'=>$newpass);
        Mail::send('email/resetpassword', $data, function($message) use($useremail, $username) {
           $message->to($useremail, $username)->subject
              ('no-reply : Reset Password Gerakan Ahsoha');
           $message->from('noreply@ahsoha.id','Gerakan Ahsoha');
        });
        return;
    }
    public function kirimEmailDonasiCicilan($useremail,$username,$id){

        // return; //sementara program dibuat agar tidak mengirim email

        $donasi=Donasi::with('donatur','rekeningbank','cicilan')->where('id',$id)->first();
        $donasino=$donasi->donasi_no;
        $tanggalakhir=DonasiCicilan::where('donasi_id',$id)->orderBy('cicilan_jatuh_tempo','desc')->first()->cicilan_jatuh_tempo;
        $donasi->donasi_tanggal_akhir=$tanggalakhir;
        try{
            $pdf = PDF::loadView('email/pdfcicilan', compact('donasi'))
            ->setPaper([0, 0, 650, 900], 'potrait'); //dalam point unit(bukan mm)
            // ->setPaper([0, 0, 209, $tinggi], 'potrait'); //dalam point unit(bukan mm)
            $filename="cicilan_donasi_".$donasino.'.pdf';
            $data = array('name'=>$username,'filename'=>$filename);
            Mail::send('email/cicilandonasi', $data, function($message) use($useremail, $username,$pdf,$filename) {
               $message->to($useremail, $username)->subject('no-reply : Cicilan Donasi');
               $message->from('noreply@ahsoha.id','Gerakan Ahsoha');
               $message->attachData($pdf->stream(), $filename);
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";
        }else{
           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }

    public function kirimEmailInvoice($useremail,$username,$idcicilan){
        // return; //sementara program dibuat agar tidak mengirim email
        $donasicicilan=DonasiCicilan::with('donasi.donatur','bayar')->where('id',$idcicilan)->first();
        $donasino=$donasicicilan->donasi->donasi_no;
        $cicilanke=$donasicicilan->cicilan_ke;
        try{                
            $pdf = PDF::loadView('email/pdfinvoice', compact('donasicicilan'))
                    ->setPaper([0, 0, 650, 900], 'potrait')->setOptions([
                        'tempDir' => public_path(),
                        'chroot'  => realpath(base_path()),
                    ]); //dalam point unit(bukan mm)
            $filename=$donasino.'-'.$cicilanke.'.pdf';
            $data = array('name'=>$username,'filename'=>$filename);
            Mail::send('email/invoice', $data, function($message) use($useremail, $username,$pdf,$filename) {
               $message->to($useremail, $username)->subject('no-reply : Invoice Donasi');
               $message->from('noreply@ahsoha.id','Gerakan Ahsoha');
               $message->attachData($pdf->stream(), $filename);
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";
        }else{
           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }
    public function simpanNotifikasiSelamatBergabung($pengirim,$tujuan){
        $isi="Selamat datang, selamat bergabung di Gerakan Ahsoha sebagai ";
        $tipe=User::where('id',$tujuan)->first()->tipe;
        if($tipe=='1'){
            $isi=$isi."Donatur";
        }
        if($tipe=='2'){
            $isi=$isi."Santri";
        }
        if($tipe=='3'){
            $isi=$isi."Pendamping";
        }

        $pesan=new Pesan;
        $pesan->pesan_pembuat_id=$pengirim;
        $pesan->pesan_tujuan_id=$tujuan;
        $pesan->pesan_tujuan_entitas=$tipe;
        $pesan->pesan_isi=$isi;
        $pesan->pesan_waktu_kirim=date("Y-m-d H:i:s");
        $pesan->pesan_status="1";
        $pesan->save();
    }
    public function simpanNotifikasiPendampingRegister($tujuan,$namapendamping){
        $isi="Pengajuan Pendamping Atas Nama ".$namapendamping." silakan ditindaklanjuti";
        $tipe=User::where('id',$tujuan)->first()->tipe;
     
        $pesan=new Pesan;
        $pesan->pesan_pembuat_id="0";
        $pesan->pesan_tujuan_id=$tujuan;
        $pesan->pesan_tujuan_entitas=$tipe;
        $pesan->pesan_isi=$isi;
        $pesan->pesan_waktu_kirim=date("Y-m-d H:i:s");
        $pesan->pesan_status="1";
        $pesan->save();
    }

    public function simpanNotifikasiErrorSistem($tujuan,$isi){
        $tipe=User::where('id',$tujuan)->first()->tipe;
        $pesan=new Pesan;
        $pesan->pesan_pembuat_id="0";
        $pesan->pesan_tujuan_id=$tujuan;
        $pesan->pesan_tujuan_entitas=$tipe;
        $pesan->pesan_isi=$isi;
        $pesan->pesan_waktu_kirim=date("Y-m-d H:i:s");
        $pesan->pesan_status="1";
        $pesan->save();
    }

    public function saveNotification($pengirim,$tujuan,$isi){
        $tipe=User::where('id',$tujuan)->first()->tipe;
        $pesan=new Pesan;
        $pesan->pesan_pembuat_id=$pengirim;
        $pesan->pesan_tujuan_id=$tujuan;
        $pesan->pesan_tujuan_entitas=$tipe;
        $pesan->pesan_isi=$isi;
        $pesan->pesan_waktu_kirim=date("Y-m-d H:i:s");
        $pesan->pesan_status='1'; //belum di baca
        $pesan->save();
    }
    public function hapusNotifikasi(){
        $deletedate=date("Y-m-d",strtotime('last weeks'));
        Pesan::where('pesan_waktu_kirim','<=',$deletedate)->update(['pesan_status'=>'3']);
        echo("===================Hapus Pesan===============\n");
    }

    public function sendWhatsApp(Request $request){
        $phoneno=$request->get('NOMOR_TUJUAN');
        $pesan=$request->get('PESAN');
        $url=$url.$method;
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
		try {
            $response = $client->post($url,
                [
                    'body' => json_encode($body),
                    'verify' => true
                ]
            );
            $result = json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return response()->json(['STATUS' => 'ER', 'MSG' => $e->getMessage()]);
        }
        if(!$result){
            return response()->json(['STATUS' => 'UN', 'KET' => 'Tidak Mendapat Respon dari Server']);
        }
        if($result->STATUS!="00"){
            return response()->json($result); 
        }
        return  response()->json(['STATUS' => 'SUCCESS', 'MSG' => 'Pesan Berhasil dikirimkan']);
    }

    public function processWhatsappMessage($phoneno, $pesan){
        $url = Config::get('ahmad.woowa.whatsapp.url');
    	$key = Config::get('ahmad.woowa.whatsapp.key');
        $method = Config::get('ahmad.woowa.whatsapp.method.sync');
        $url=$url.$method;
        $body = array(
            "key"         => $key,
            "phone_no"    => $phoneno,
            "message"     => $pesan,
            "skip_link"   => True,
        );

        $data_string = json_encode($body);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $res=curl_exec($ch);
        curl_close($ch);

        // json_decode($res)
        return  $res;
    }
}

