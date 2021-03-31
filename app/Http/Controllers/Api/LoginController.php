<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Agent;

class LoginController extends Controller
{


    public function login(Request $request)
    {
        if(!Agent::device()){
            // return response()->json(['code'=>1,'msg'=>'您是一个机器人']);
        }

        $device_name =  Agent::device()?:'apk';

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'device_name' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return response()->json(['code'=>0,'msg'=>'登录成功','data'=>['token'=>$user->createToken($device_name)->plainTextToken]]);
    }
    
}
