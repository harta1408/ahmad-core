<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lembaga;
use App\Models\FAQ;
use App\Http\Controllers\KodePosAPI;
use GeniusTS\HijriDate\Date;
use GeniusTS\HijriDate\Hijri;
use GeniusTS\HijriDate\Translations\Indonesian;

class LembagaController extends Controller
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
        $lembaga=Lembaga::where('lembaga_id','ahmad')->first();
        if(!$lembaga){
            $lembaga=new Lembaga;
            $lembaga->lembaga_id='ahmad';
            $lembaga->lembaga_email='helpdesk@ahmadproject.com';
            $lembaga->save();
        }
        
        return view('master/lembaga',compact('lembaga'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
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
        $res = Lembaga::where('lembaga_id', $id)->update($request->except(['lembaga_id','_token','_method']));

        if (!$res) {
            return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        }
        
        $provinsiid=$request->get('lembaga_provinsi_id');
        $kotaid=$request->get('lembaga_kota_id');
        $kodeposapi=new KodePosAPI;
        $provinsi=$kodeposapi->getProvisiById($provinsiid);
        $kota=$kodeposapi->getKotaById($kotaid);

        Lembaga::where('lembaga_id',$id)->update(['lembaga_kota'=>$kota,'lembaga_provinsi'=>$provinsi]);
        
        return redirect()->action('LembagaController@index');
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

    #penyesuaian tanggal hijriah
    public function hijriahIndex(){
        $lembaga=Lembaga::first();
        if(!$lembaga){
            $lembaga=new Lembaga;
            $lembaga->lembaga_id='ahmad';
            $lembaga->lembaga_email='helpdesk@ahmadproject.com';
            $lembaga->save();
        }

        $adjhijr=$lembaga->lembaga_adjust_hijr;
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $hariini=$today->format('d F o');

        Hijri::setDefaultAdjustment(0);
        $today = Date::today();
        $haridefault=$today->format('d F o');

        return view('master/hijriah',compact('lembaga','hariini','haridefault'));
    }

    public function hijriahUpdate($adjhijr){
        Hijri::setDefaultAdjustment($adjhijr);
        Date::setTranslation(new Indonesian);
        $today = Date::today();
        $hijrbaru=$today->format('d F o');
        return $hijrbaru;
    }
    public function hijriahSave(Request $request){
        $adjust=$request->get('adjust');
        Lembaga::where('lembaga_id','ahmad')->update(['lembaga_adjust_hijr'=>$adjust]);
        return response()->json(['status' => 'success', 'message' => 'Berhasil di perbaharui', 'code' => 200]);
    }
}
