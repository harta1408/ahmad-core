<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
class UserAPI extends Controller
{
    public function registerUser(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'user_name' => ['required','string','max:30'],
            'user_email' => 'required|email', 
            'user_password' => 'required', 
            'user_password_confirm' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['status' => 'error', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        try{
            $user=new User;
            $user->user_name=$request->get('user_name');
            $user->user_email=$request->get('user_email');
            $user->user_hash=md5(round(1000,9999));
            $user->email=$request->get('user_emai');
            $user->user_tipe=$request->get('user_tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin
            $user->password=Hash::make($request->input('user_password'));
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
        $username=$request->user_name;
        $password=$request->user_password;
        $usertipe=$request->get('user_tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin
 
        if($usertipe=='1'){
            $user= User::with('donatur')->where('username',$username)->first();
        }else if($usertipe=='2'){
            $user= User::with('santri')->where('username',$username)->first();
        }else{
            $user= User::where('username',$username)->first();
        }

    	if(!$user){
            return response()->json(['status'=>'error','message'=>'User not found','user'=>$user]); 
        }
        if(Hash::check($request->password, $password)){
            return response()->json(['status'=>'success','message'=>'OK','user'=>$user]); 
		}else{
			return response()->json(['status'=>'error','message'=>'Password didnt match','user'=>$user]); 
        }
    }
  
    public function userChangePassword($id,Request $request){
        $password=Hash::make($request->get('user_password')); 
        $usertipe=$request->get('user_tipe'); // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin

        $exec=User::where('id','=' ,$id)
        ->update(['user_password'=>$password]);

        if($usertipe=='1'){
            $user= User::with('donatur')->where('username',$username)->first();
        }else if($usertipe=='2'){
            $user= User::with('santri')->where('username',$username)->first();
        }else{
            $user= User::where('username',$username)->first();
        }        
        return response()->json($user,200);
    }
   
   
}
