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
            'tipe' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $email=$request->email;
        $password=$request->password;
        $tipe=$request->get('tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin
        if($tipe=='1'){
            $user= User::with('donatur')->where('email',$email)->first();
        }else if($tipe=='2'){
            $user= User::with('santri')->where('email',$email)->first();
        }else{
            $user= User::where('email',$email)->first();
        }
    	if(!$user){
            return response()->json(['status' => 'error', 'message' => 'User not found', 'code' => 404]);
        }

        $user= User::where('email',$email)->first();
        if(Hash::check($request->password, $user->password)){
            return response()->json($user,200); 
		}else{
            return response()->json(['status' => 'error', 'message' => 'Password didnt match', 'code' => 404]);
        }
    }
  
    public function userChangePassword($id,Request $request){
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
            'tipe' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        $password=Hash::make($request->get('password')); 
        $email=$request->email;
        $tipe=$request->get('tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin

        $exec=User::where('id','=' ,$id)
        ->update(['password'=>$password]);

        // if($tipe=='1'){
        //     $user= User::with('donatur')->where('email',$email)->first();
        // }else if($tipe=='2'){
        //     $user= User::with('santri')->where('email',$email)->first();
        // }else{
            $user= User::where('email',$email)->first();
        // }        
        return response()->json($user,200);
    }
   
   
}
