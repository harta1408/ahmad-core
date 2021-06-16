<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produk;
use Validator;
use App\Http\Controllers\HomeController;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
     {
         $this->middleware('auth');
     }
    public function index()
    {
        return view('produk/produknew');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_nama' => 'required|max:100',
            'produk_harga' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $produk=new Produk; 
        $produk->produk_nama=$request->get("produk_nama"); 
        $produk->produk_deskripsi=$request->get("produk_deskripsi");
        $produk->produk_lokasi_gambar=$request->get("produk_lokasi_gambar");
        $produk->produk_lokasi_video=$request->get("produk_lokasi_video");
        $produk->produk_harga=$request->get("produk_harga");
        $produk->produk_discount=$request->get("produk_discount");
        $produk->produk_stok=$request->get("produk_stok"); 
        $produk->produk_status='1'; //aktif belum melengkapi data
        $produk->save();

        return redirect()->action('DonaturController@index');
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
        $produk=Produk::where('id',$id)->first();

        return view('produk/produkupdate',compact('produk'));
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
        $res = Produk::where('id', $id)->update($request->except(['id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        return redirect()->action('HomeController@index');
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
