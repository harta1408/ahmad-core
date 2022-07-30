<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
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
        $user=User::where('approve','1')->get();
        return view('securities/userindex',compact('user'));
    }
    public function userMain(Request $request){
        $stat=$request->userstate;
        $id=$request->userid;

        // $stores=Stores::where('store_state','!=','0')->get();
        // $role=Role::where('name','!=','superadmin')->get();

        if($stat=="NEW"){ 
            return view('securities/usernew');
        }
        if($stat=="UPDATES"){
            $user=User::where('id',$id)->first(); 
            return view('securities/userupdate',compact('user'));
        }
        if($stat=="RESET"){
            $user=User::where('id',$id)->first(); 
            return view('securities/userpwdreset',compact("user"));
        }
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
            '*.tipe' => 'required|string|unique:users',
            '*.name' => 'required|string',
            '*.email' => 'required|string|unique:users',
        ]);
        // $roleids = $request->input('form')['role']; 
        // $storeid = $request->input('form')['store'];
        if ($validator->fails()) {
            return response()->json(['status' => 'Error Validasi', 'message' => $validator->messages()->first(), 'code' => 404]);
        }

        //spatie
        $tipe=$request->input('form')['tipe'];
        $role=Role::findById($tipe);
    
        try{
            $user=new User;
            $user->assignRole($role);
            $user->name=$request->input('form')['name'];
            $user->email=$request->input('form')['email'];
            $user->tipe=$tipe;
            $user->approve='1'; //karena dibuat, maka otomatis approved
            $user->email_verified_at=date("Y-m-d H:i:s");
            $user->password=Hash::make($request->input('form')['password']);
            $execute = $user->save();
            if (!$execute) {
                return response()->json(['status' => 'error', 'message' => 'System error', 'code' => 404]);
            }
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Simpan', 'code' => 200]);
        }catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'code' => 404]);
        }
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
    
    public function userPasswordReset(Request $request,$id){
        $password=Hash::make($request->input('form')['password']);

        User::where('id', $id)->update(['password'=>$password]);

        // $res = User::where('id', $id)->update($request->input('form')->except(['id','_method']));

        // if (!$res) {
        //     return response()->json(['status' => 'error', 'message' => 'System Error', 'code' => 404]);
        // }
        return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Perbaharui', 'code' => 200]);
    }
    public function userApproveIndex(){
        // $user=User::where('approve','0')->get();
        return view('securities/userapproveindex');
    }
    public function userApprovalLoad(){
        $user=User::where('approve','0')->get();
        foreach ($user as $key => $usr) {
            if($usr->approve==0){
                $usr->approve=false;
            }
        }
        return $user;
    }
    public function userApprovalUpdate($id, Request $request){
        if($request->approve=='true'){
            User::where('id',$id)->update([
                'approve' => '1',
            ]);
        }
    }

    #------------Administrasi pengaturan pengguna
    public function admUserIndex(){
        $user=User::where('tipe','!=','6')->get();
        return view("securities/adm/userindex",compact('user'));
    }
    public function admUserMain(Request $request){
        $status=$request->userstate;
        $id=$request->userid;
        $user=User::where('id',$id)->first(); 
        return view('securities/adm/userpwdreset',compact("user"));
    }

    public function admUserResetPassword(Request $request,$id){
        $password=Hash::make($request->input('form')['password']);
        User::where('id', $id)->update(['password'=>$password]);
        return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Perbaharui', 'code' => 200]);
    }
}
