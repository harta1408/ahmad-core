<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Santri;

class DonasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('donasi/donasiindex');
    }
    public function main(Request $request){
        
        // dd($request->donasi_stat);
        $arrDonasi=array();
        $pjg=strlen($request->donasi_selected);
        $donasiselected=substr($request->donasi_selected,0,$pjg-1);

        $donasiselected=explode(",",$donasiselected);
        $donasi=Donasi::whereIn('id',$donasiselected)->get();
        #hitung jumlah santri yang di butuhkan
        $jmlsantridonasi=Donasi::whereIn('id',$donasiselected)->sum('donasi_jumlah_santri');

        #hitung jumlah santri yang ada
        $jmlsantri=Santri::where('santri_status',4)->count();
        if($jmlsantri<$jmlsantridonasi){
            //kekurangan santri
            return response()->json(['status' => 'error', 'message' => 'Jumlah Santri tidak mencukupi', 'code' => 404]);
        }

        #ambil santri aktif dengan data lengkap dan belum pernah
        #mengikuti bimbingan
        $santri=Santri::where('santri_status',4)->pluck('id')->toArray();
        
        $randomsantri=array_rand($santri,$jmlsantridonasi);
        // dd($randomsantri);

        #masukan data dummy
        if($jmlsantridonasi==1){
            foreach ($donasi as $key => $dns) {    
                //  $dns->donasi_donatur_nama=Donatur::where('id', $dns->donatur_id)->first()->donatur_nama;
                //  $dns->donasi_santri_id=Santri::where('id',$santri[$randomsantri])->first()->id;
                //  $dns->donasi_santri_nama=Santri::where('id',$santri[$randomsantri])->first()->santri_nama;
                $donaturid=$dns->donatur_id;
                $donaturnama=Donatur::where('id',$donaturid)->first()->donatur_nama;
                $santriid=Santri::where('id',$santri[$randomsantri])->first()->id;
                $santrinama=Santri::where('id',$santri[$randomsantri])->first()->santri_nama;
                $arrDonasi[]= array('id' => $dns->id,
                    'donasi_no' => $dns->donasi_no,
                    'donatur_id'=>$donaturid,
                    'donasi_donatur_nama' =>$donaturnama,
                    'donasi_santri_id' => $santriid,
                    'santri_nama' => $santrinama,
                );
            }
        }else{
            $j=0;
            foreach ($donasi as $key => $dns) {    
                $jumlahdonasi=$dns->donasi_jumlah_santri;  
                $donaturid=$dns->donatur_id;
                $donaturnama=Donatur::where('id',$donaturid)->first()->donatur_nama;
                for ($i=0; $i < $jumlahdonasi; $i++) { 
                    $santriid=Santri::where('id',$santri[$randomsantri[$j]])->first()->id;
                    $santrinama=Santri::where('id',$santri[$randomsantri[$j]])->first()->santri_nama;
                    $arrDonasi[]= array('id' => $dns->id,
                        'donasi_no' => $dns->donasi_no,
                        'donatur_id'=>$donaturid,
                        'donasi_donatur_nama' =>$donaturnama,
                        'donasi_santri_id' => $santriid,
                        'santri_nama' => $santrinama,
                    );
                    $j++;
                }
            }
        }

        $donasi=json_encode($arrDonasi);

        // dd($donasi);

        #ambil data santri dengan status 4 untuk kemungkinan penggantian santri
        $santri=Santri::where('santri_status','4')->get();
        return view('donasi/donasikonfirmasi',compact('donasi','santri'));

        return response()->json(['status' => 'success', 'message' => 'Penyimpanan berhasil', 'code' => 200]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $donasi=Donasi::with('donatur')->where('donasi_status','2')->get();
        return $donasi;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        $jmlsantridonasi=count($request->dataDonasi);
        // for ($i=0; $i < count($request->dataDonasi); $i++) { 
        //     $jmlsantridonasi=$jmlsantridonasi+$request->get('dataDonasi')[$i]['donasi_jumlah_santri'];
        // }

        #jika hanya satu record returnya bukan array
        if($jmlsantridonasi==1){
            #simpan data donasi santri
            $donasi=$request->get('dataDonasi')[0];
            $donaturid=$donasi['donatur_id'];
            $santriid=$donasi['donasi_santri_id'];
            $donatur=Donatur::where('id',$donaturid)->first();
            $donatur->santri()->attach(['donatur_id'=>$donaturid],[
                'santri_id' =>$santriid,
                'donasi_id' =>$donasi['id'],
                'donatur_santri_status' =>'0',
            ]);
            #update status santri sudah menerima donasi
            Santri::where('id',$santriid)->update(['santri_status'=>'5']);
            #update donasi sudah tersalurkan ke santri
            Donasi::where('id',$donasi['id'])->update(['donasi_status'=>'3']);
        }else{
            #periksa apakah ada data santri yang ganda
            for ($i=0; $i < $jmlsantridonasi; $i++) { 
                $santriid=$request->get('dataDonasi')[$i]['donasi_santri_id'];
                for ($j=$i+1; $j < $jmlsantridonasi; $j++) { 
                    $santricek=$request->get('dataDonasi')[$j]['donasi_santri_id'];
                    if($santriid==$santricek){
                        return response()->json(['status' => 'error', 'message' => 'Tidak dapat dilanjutkan, ada data santri yang sama', 'code' => 404]);
                    }
                }
            }
            for ($i=0; $i < count($request->dataDonasi); $i++) { 
                #simpan data donasi santri
                $id=$request->get('dataDonasi')[$i]['id'];
                $donaturid=$request->get('dataDonasi')[$i]['donatur_id'];
                $santriid=$request->get('dataDonasi')[$i]['donasi_santri_id'];
                $donatur=Donatur::where('id',$donaturid)->first();
                $donatur->santri()->attach(['donatur_id'=>$donaturid],[
                    'santri_id' =>$santriid,
                    'donasi_id' =>$id,
                    'donatur_santri_status' =>'0',
                ]);
                #update status santri sudah menerima donasi
                Santri::where('id',$santriid)->update(['santri_status'=>'5']);
                #update donasi sudah tersalurkan ke santri
                Donasi::where('id',$id)->update(['donasi_status'=>'3']);
            };
        }
        return response()->json(['status' => 'success', 'message' => 'Produk Berhasil di Distribusikan', 'code' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
