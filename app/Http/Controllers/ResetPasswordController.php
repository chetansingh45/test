<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use Illuminate\Http\Request;
use App\Models\AuthModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
date_default_timezone_set("Asia/kolkata");
class ResetPasswordController extends Controller
{

    public function showReset(){
        return view('auth.reset');
    }

    public function reset(Request $request)
    {
        $token = rand(000000, 999999) . strtotime(date("d/m/y h:i:s"));
        $email = $request->email;
        $user = AuthModel::where("email", '=', $email)->first();
        if ($user) {
            ResetPassword::where("email",$email)->delete();
            $data = ["email" => $email, "token" => $token];
            if (ResetPassword::insert($data)) {

                Mail::to($email)->send(new ResetPasswordMail($data));
                return back()->with("success", "Reset link sended on your mail");
            } else {
                return back()->with("error", "Incorrect Email Please Check Your Email");
            }
        }
    }

    public function checkReset($email,$token){
        $reset = ResetPassword::where("email",'=',$email)->where('token','=',$token)->first();
        if($reset){
            $createdTime = $reset->created_at;
            $expireTime = Carbon::parse($createdTime)->addMinute(2);
            $currentTime = Carbon::now();

            $data = ["email"=>$email,"token"=>$token];
            if($currentTime->gt($expireTime)){
                return redirect()->route('auth.get')->with("linkExpired","Link Expired");
            }
            return view('auth.newpassword',["data"=>$data]);
        }else{
            return redirect()->route('auth.get')->with("linkExpired","Link Expired");
        }
    }

    public function changePassword(Request $request){
        $request->validate([
            "password"=>"required|confirmed|min:4"
        ]);
        
        $reset = ResetPassword::where("email",'=',$request->email)->where('token','=',$request->token)->first();
        if($reset){
            $user = AuthModel::where("email",$request->email)->update(["password"=>$request->password]);
            if($user){
                $delete=ResetPassword::where("email",$request->email)->delete();
                if($delete){
                    return redirect()->route('get.dashboard');
                }
            }
        }
    }
}
