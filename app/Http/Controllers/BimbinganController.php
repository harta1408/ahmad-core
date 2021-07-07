<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bimbingan; 
use App\Models\Produk;

class BimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bimbingan/bimbinganlist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bimbingan=Bimbingan::with('santri','pendamping','produk')->whereIn('bimbingan_status',['0','1'])->get();
        foreach ($bimbingan as $key => $bm) {
            $status=$bm->bimbingan_status;
            if($status=='0'){
                $bm->bimbingan_status='Menunggu';
            }
            if($status=='1'){
                $bm->bimbingan_status='Dalam Bimbingan';
            }
        }
        return $bimbingan;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function bimbinganSantriMulai($santriid,$mulai){
        #proses bimbingan katika produk sampai di santri
        $produkid=Bimbingan::where('santri_id',$santriid)->first()->produk_id;
        $dayno=Produk::where('id',$produkid)->first()->produk_masa_bimbingan;

        #hitung penambahan tanggal untuk menentukan tanggal akhir
        $akhir=date('Y-m-d',strtotime($mulai.' '.$dayno." days"));

        #pebaharui data
        Bimbingan::where('santri_id',$santriid)->update([
            'bimbingan_mulai'=>$mulai,
            'bimbingan_berakhir' =>$akhir,
            'bimbingan_status'=>'1']);
    }
}
