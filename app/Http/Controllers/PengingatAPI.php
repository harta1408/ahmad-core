<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Pengingat;
use App\Models\DOnasi;
use App\Models\Donatur;
use App\Models\Santri;
use App\Models\Pendamping;
use App\Models\User;
use App\Models\Lembaga;
use App\Models\PengingatDonatur;
use App\Http\Controllers\Service\MessageService;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Translations\Indonesian;
use Validator;
use DB;

class PengingatAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}

    #pengingat API DOnatur berfungsi mengambil, pengingat diacak pada tabel pengingat donatur
    #pembuatan pengingat donatur dilakukan melalui Donasi Service setiap hari secara otomatis
    public function pengingatDonaturById($donaturid){
        $pengingatdonatur=PengingatDonatur::where([['donatur_id',$donaturid],['pengingat_donatur_status','1']])->pluck('pengingat_id');
        $pengingat=Pengingat::whereIn('id',$pengingatdonatur)->get();
        $todaydate=date("Y-m-d");

        $adjhijr=Lembaga::first()->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $dayhijr=Date::today()->format('d');
        
        if(date('w', strtotime($todaydate))!=5){ 
            #jika harian tampilkan sedekah subuh
            $pengingatid=$this->findPengingatJenis($pengingat,'1');
        }else if(date('w', strtotime($todaydate))==5){ //5:friday
            #jika jumat tampilkan sedekan pekanan
            $pengingatid=$this->findPengingatJenis($pengingat,'2');
        } 
        
        if($dayhijr=='13' || $dayhijr=='14' || $dayhijr=='15'){
            #jika yaumil bidh 13,14,15 munculkan pengingat yaumil bidh
            $pengingatid=$this->findPengingatJenis($pengingat,'3');
        }

        #tidak ada pengingat untuk ditampilkan sesuai kategori
        if($pengingatid==""){
            return response()->json(['status' => 'error', 'message' => 'Tidak ada pengingat untuk ditampilkan', 'code' => 404]);
        }
    
        $donatur=function ($query) use ($donaturid){
            $query->where([['id',$donaturid],['pengingat_donatur_status','1']]);
        };
        $pengingat=Pengingat::where('id',$pengingatid)->with(['donatur'=>$donatur])->whereHas('donatur',$donatur)->first();
        return response()->json($pengingat,200);  
    }
    private function findPengingatJenis($pengingat,$jenis){
        foreach ($pengingat as $key => $pngt) {
            if($pngt->pengingat_jenis==$jenis){
                return $pngt->id;
            }
        }
        return "";
    }
    public function pengingatDonaturRespon($id,Request $request){
        #update status donatur
        $donaturid=$request->donatur_id;
        $respon=$request->donatur_respon;
        $donatur = Donatur::find($donaturid);
        $donatur->pengingat()->updateExistingPivot($id, [
            'pengingat_donatur_respon' => $respon,
        ]);

        $pengingat=Pengingat::with('donatur')->where('id',$id)->first();
        return response()->json($pengingat,200);  
    }

    #pengingat untuk keperluan santri
    
    public function pengingatSantriById($santriid){
        $santri=function ($query) use ($santriid){
            $query->where([['id',$santriid],['pengingat_santri_status','1']]);
        };
        $pengingat=Pengingat::with(['santri'=>$santri])->whereHas('santri',$santri)->first();
        return response()->json($pengingat,200);
    }
    public function pengingatSantriRespon($id,Request $request){
        #update status donatur
        $santriid=$request->santri_id;
        $respon=$request->santri_respon;
        $santri = Santri::find($santriid);
        $santri->pengingat()->updateExistingPivot($id, [
            'pengingat_santri_respon' => $respon,
        ]);
        $pengingat=Pengingat::with('santri')->where('id',$id)->first();
        return response()->json($pengingat,200);  
    }

    #pengingat untuk keperluan pendamping
    public function pengingatListByPendampingId($pendampingid){
        $pendamping=function ($query) use ($pendampingid){
            $query->where('id',$pendampingid);
        };
        $pengingat=Pengingat::with(['pendamping'=>$pendamping,'santri'])->whereHas('pendamping',$pendamping)->get();
        return response()->json($pengingat,200);    
    }

    #simpan pengingat oleh pendamping untuk mengingatkan 7=online meeting 8=offline meeting 9=talkin dzikir
    public function pengingatSimpan(Request $request){
        $validator = Validator::make($request->all(), [
            'pendamping_id' => 'required|string|',
            'pengingat_judul' => 'required|string|',
            'pengingat_isi_singkat' => 'required|string|',
            'pengingat_isi' => 'required|string|',
            'pengingat_jenis' => 'required|string|',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        $pengingatentitas='3'; //pendamping
        $pendampingid=$request->get('pendamping_id');
        $pendamping=Pendamping::where('id',$pendampingid)->first();
        if(!$pendamping){
            return response()->json(['status' => 'error', 'message' => 'Pendamping tidak ditemukan', 'code' => 404]);
        }

        $judul=$request->get('pengingat_judul');
        $pengingat=new Pengingat;
        $pengingat->pengingat_judul=$judul;
        $pengingat->pengingat_isi_singkat=$request->get('pengingat_isi_singkat');
        $pengingat->pengingat_isi=$request->get('pengingat_isi');
        $pengingat->pengingat_jenis=$request->get('pengingat_jenis');
        $pengingat->pengingat_entitas='2'; //santri
        $pengingat->pengingat_lokasi_gambar=$request->get('pengingat_lokasi_gambar');
        $pengingat->pengingat_lokasi_video=$request->get('pengingat_lokasi_video');
        $pengingat->pengingat_status='1';//aktif
        $exec=$pengingat->save();

        $msg=new MessageService;

        $pengingatid=$pengingat->id;
        $pendampingid=$request->get('pendamping_id');
        for ($i=0; $i < count($request->input('santri')) ; $i++) {
            #simpan pengingat pada pengingat pendamping
            $santriid=$request->input('santri')[$i]['santri_id'];
            $pengingat->pendamping()->attach(['pengingat_id'=>$pengingatid],
            [
                'pendamping_id'=>$pendampingid, 
                'santri_id'=>$santriid, 
                'pengingat_pendamping_status'=>'1', //aktif
            ]);     
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
                'pengingat_santri_status'=>'1', //aktif
            ]);    

            #kirim pesan untuk santri
            $pengirim='3'; //dari pendamping
            $emailsantri=Santri::where('id',$santriid)->first()->santri_email;
            $tujuan=User::where('email',$emailsantri)->first()->id;
            $isi='Anda mendapat Undangan '.$judul.' dari pendamping '.$pendamping->pendamping_nama;
            $msg->saveNotification($pengirim,$tujuan,$isi);
        }

        $pendamping=Pendamping::with('pengingat','santri')->where('id',$pendampingid)->first();

        #kirikan pesan untuk pendamping
        $pengirim='0'; //dari sistem
        $emailpendamping=Pendamping::where('id',$pendampingid)->first()->pendamping_email;
        $tujuan=User::where('email',$emailpendamping)->first()->id;
        $isi='Undangan '.$judul.' telah dikirimkan kepada Santri ';
        $msg->saveNotification($pengirim,$tujuan,$isi);

        if(!$exec){
            return response()->json(['status' => 'error', 'message' => "Data Cannot be Save", 'code' => 404]);
        }
        return response()->json($pendamping,200);  
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

}
