<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Lembaga;
use App\Models\RekeningBank;
use App\Models\Faq;

class LembagaAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function getLembaga(){
        $lembaga=Lembaga::with('faq')->first();
        return response()->json($lembaga,200);

    }
    public function getRekeningBankList(){
        $rekeningbank=RekeningBank::where('rekening_status','1')->get();
        return response()->json($rekeningbank,200);
    }
}
