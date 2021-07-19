<?php

namespace App\Http\Controllers;
use App\Models\AuthModel;
use Illuminate\Http\Request;
use App\Mail\RegistrationNotification;
use Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function index()
    {
        
        return view('Auth.index');
    }

    public function dashboard()
    {
        return view("admin.index");
    }

    public function login(Request $request)
    {

        $user = new AuthModel;
     
        if ( Auth::attempt(['email' => $request->data["email"], 'password' => $request->data["password"]])) {
            return response()->json(["code" => 1, "msg" => "Login success", "desc" => "Redirecting on dashboard. Please wait", "color" => "success"]);
        } else {
            return response()->json(["code" => 0, "msg" => "Login Failed", "desc" => "Email not registered with us", "color" => "error"]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('auth.get');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => "required|min:2",
            'email' => "required|email|unique:users",
            'password' => "required|min:4|max:12"
        ]);
        if (!$validator->passes()) {
            return response()->json(["code" => 0, "error" => $validator->errors()->toArray()]);
        }

        $user = new AuthModel;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $res = $user->save();
       
        if ($res) {
            Mail::to($user->email)->send(new RegistrationNotification($user->username));
            return response()->json(["code" => 1, "msg" => "Registration success", "desc" => "Fetching Login Form Please wait for a second", "color" => "success","user"=>$user]);
        }
    }

    public function addUser(){
        return view('admin.addUser');
    }

    public function showUsers(){
        $users=AuthModel::all();
        return view("admin.users",["users"=>$users]);
    }

    public function destroy(Request $request)
    {
       $res=AuthModel::where('id','=',$request->data['id'])->delete();

       if($res){
           return response()->json(["code"=>1,"msg"=>"user deleted successfully"]);
       }else{
           return response()->json(["code"=>0,"msg"=>"something went wrong please try agian"]);
       }
       
     }

     public function editUser(Request $request){
        $res=AuthModel::where('id','=',$request->data['id'])->first();
        return response()->json(["user" =>$res]);
     }

     public function updateUser(Request $request){
         $user = AuthModel::where("id",$request->userId)->update(["username"=>$request->username,"email"=>$request->email,"password"=>$request->password]);
         if($user){
             return response()->json(["code" =>"1","msg"=>"User Updated","desc"=>"User updated successfully","color" =>"success","data"=>$request->input()]);
         }else{
             return response()->json(["code" =>"0","msg" =>"Not updated","desc" =>"User not updated","color" =>"error"]);
         }
     }

}
