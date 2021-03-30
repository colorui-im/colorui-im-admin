<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $user->load('divideGroups.users.tokens','groups.users.tokens','tokens','friendGroups.users');

        $user = new UserResource($user);

        return response()->json(['code'=>0,'msg'=>'','data'=>['user'=>$user]]);

    }
    public function users(Request $request)
    {
        $users = User::with('tokens')->get();
        $users = UserResource::collection($users);
        return response()->json(['code'=>0,'msg'=>'','data'=>['lists'=>$users]]);

    }
}
