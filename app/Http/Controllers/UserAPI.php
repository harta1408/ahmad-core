<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
class UserAPI extends Controller
{
    public function userLogin(Request $request){
        $username=$request->user_name;
        $password=$request->user_password;
 
        $user= User::with('santri')->where('username',$username)->first();
    	if(!$user){
            return response()->json(['status'=>'error','message'=>'User not found','user'=>$user]); 
        }
        if(Hash::check($request->password, $password)){
            return response()->json(['status'=>'success','message'=>'OK','user'=>$user]); 
		}else{
			return response()->json(['status'=>'error','message'=>'Password didnt match','user'=>$user]); 
        }

    }
    public function userCreate(Request $request){

    }

    public function userChangePassword($id,Request $request){
        $password=Hash::make($request->get('user_password')); 

        $exec=User::where('id','=' ,$id)
        ->update(['user_password'=>$password]);

        $user=User::with('santri')->where('id',$id)->first();
        return response()->json($user,200);
    }
}
