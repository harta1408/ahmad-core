<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Pesan;
use App\Models\User;
use Config;

class MessageService extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}

    public function simpanNotifikasiSelamatBergabung($pengirim,$tujuan){
        $isi="Selamat data, selamat bergabung di AHMaD Project sebagai ";
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

    public function saveNotification($pengirim,$tujuan,$isi){
        $tipe=User::where('id',$tujuan)->first()->tipe;
        $pesan=new Pesan;
        $pesan->pesan_pembuat_id=$pengirim;
        $pesan->pesan_tujuan_id=$tujuan;
        $pesan->pesan_tujuan_entitas=$tipe;
        $pesan->pesan_isi=$isi;
        $pesan->pesan_waktu_kirim=date("Y-m-d H:i:s");
        $pesan->save();
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
            dd($result);
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

