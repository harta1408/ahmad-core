<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;
class UserAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
	}
    public function registerUser(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => ['required','string','max:30'],
            'email' => 'required|email', 
            'password' => 'required', 
            'password_confirm' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        try{
            $user=new User;
            $user->name=$request->get('name');
            $user->email=$request->get('email');
            $user->user_hash=md5(round(1000,9999));
            $user->email=$request->get('user_emai');
            $user->tipe=$request->get('tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin
            $user->password=Hash::make($request->input('password'));
            $execute = $user->save();
            if (!$execute) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
        }catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Simpan', 'code' => 200]);
    }
    public function userLogin(Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }
        
        $email=$request->email;
        $password=$request->password;
        $user= User::where('email',$email)->first();
        if(!$user){
            return response()->json(['status' => 'error', 'message' => 'User not found', 'code' => 404]);
        }
        if($user->gmail_state=='1'){
            return response()->json(['status' => 'error', 'message' => 'User Already Registerd with GMail', 'code' => 404]);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json(['status' => 'error', 'message' => 'Password didnt match', 'code' => 404]);
        }
        $tipe=$user->tipe; // 1=donatur 2=santri, 3=pendamping 
        if($tipe=='1'){ //donatur
            $user= User::with('donatur')->where('email',$email)->first();
        }
        if($tipe=='2'){ //santri
            $user= User::with('santri')->where('email',$email)->first();
        }
        if($tipe=='3'){ //pendamping
            $user= User::with('pendamping')->where('email',$email)->first();
        }
        return response()->json($user,200); 
    }
    public function userLoginGMail(Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $email=$request->email;
        $password=$request->password;
        $user= User::where('email',$email)->first();
        
        if(!$user){
            return response()->json(['status' => 'error', 'message' => 'User not found', 'code' => 404]);
        }
        if($user->gmail_state=='0'){
            return response()->json(['status' => 'error', 'message' => 'User Already Registerd', 'code' => 404]);
        }

        $tipe=$user->tipe; // 1=donatur 2=santri, 3=pendamping 
        if($tipe=='1'){ //donatur
            $user= User::with('donatur')->where('email',$email)->first();
        }
        if($tipe=='2'){ //santri
            $user= User::with('santri')->where('email',$email)->first();
        }
        if($tipe=='3'){ //pendamping
            $user= User::with('pendamping')->where('email',$email)->first();
        }
        return response()->json($user,200); 
    }

    public function userChangePassword($id,Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $password=Hash::make($request->get('password')); 
        $email=$request->email;
        User::where('id','=' ,$id)->update(['password'=>$password]);

        $user= User::where('email',$email)->first();
        $tipe=$user->tipe; // 1=donatur 2=santri, 3=pendamping 
        if($tipe=='1'){ //donatur
            $user= User::with('donatur')->where('email',$email)->first();
        }
        if($tipe=='2'){ //santri
            $user= User::with('santri')->where('email',$email)->first();
        }
        if($tipe=='3'){ //pendamping
            $user= User::with('pendamping')->where('email',$email)->first();
        }       
        return response()->json($user,200);
    }
   
   public function userByHashCode($hashcode){
        $user=User::where([['hash_code',$hashcode],['approve',"0"]])->first();
        if(!$user){
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan', 'code' => 404]);
        }
        $userid=$user->id;
        $tipe=$user->tipe; // 1=donatur 2=santri, 3=pendamping 
        if($tipe=='1'){ //donatur, bisa jadi sudah berdonasi
                $user=User::with('donatur.donasi.produk','donatur.donasi.rekeningbank')->where('id',$userid)->first();
        }
        if($tipe=='2'){ //santri
            $user= User::with('santri')->where('id',$userid)->first();
        }
        if($tipe=='3'){ //pendamping
            $user= User::with('pendamping')->where('id',$userid)->first();
        } 
        return response()->json($user,200);
   }

   public function userVerification($id, Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $password=Hash::make($request->get('password')); 
        $email=$request->email;
        $emailverification=date("Y-m-d H:i:s");
        User::where('id','=' ,$id)->update(['password'=>$password,'email_verified_at'=>$emailverification]);

        $user= User::where('email',$email)->first();
        $tipe=$user->tipe; // 1=donatur 2=santri, 3=pendamping 
        if($tipe=='1'){ //donatur
            $user= User::with('donatur')->where('email',$email)->first();
        }
        if($tipe=='2'){ //santri
            $user= User::with('santri')->where('email',$email)->first();
        }
        if($tipe=='3'){ //pendamping
            $user= User::with('pendamping')->where('email',$email)->first();
        }       
        return response()->json($user,200);   
   }
}
