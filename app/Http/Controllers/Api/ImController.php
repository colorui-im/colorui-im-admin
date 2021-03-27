<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ImRequest;
use Agent;
use App\Models\User;
use App\Models\Group;
use App\Http\Resources\UserResource;
use App\Http\Resources\GroupResource;

class ImController extends Controller
{
    public function bindUid(ImRequest $request)
    {
        $user = $request->user();

        // dd($user);
        // return ['code'=>0,'msg'=>'ok','data'=>['token'=>$user->currentAccessToken()->tokenable]];

        $currentAccessToken = $user->currentAccessToken();
        $gateway = app('gateway');
        $gateway->bindUid($request->client_id, $currentAccessToken->id);

        $groups = $user->groups()->get();
        $friendGroups = $user->friend_groups()->get();

        foreach ($groups as $group){//大群
            $gateway->joinGroup($request->client_id,$group->id);
        }

        foreach ($friendGroups as $friendGroup){//和好友的群,只有两人
            $gateway->joinGroup($request->client_id,$friendGroup->id);
        }

        return response()->json(['code'=>0,'msg'=>'','data'=>[]]);
    }

    public function send(ImRequest $request)
    {
        $gateway = app('gateway');
        $user = $request->user();

        $currentAccessToken = $user->currentAccessToken();

        $currentClientId = $gateway->getClientIdByUid($currentAccessToken->id);
        $data = $request->validationData();


        $data['from'] = (new UserResource($user));
        $data['from_id'] = $user->id;

        $data['sended_at'] = now()->format('Y-m-d H:i:s');



        switch($data['type']){
            case 'group':

                unset($data['client_id']);
                $toGroup = Group::findOrFail($data['to']['id']);
                $data['to'] = (new GroupResource($toGroup));
                $data['to_id'] = $toGroup->id;
                $gateway->sendToGroup($data['to']['id'],$data,$currentClientId);
                break;
            case 'friend':

                // $toUser = User::findOrFail($data['to']['id']);
                // $data['to'] = (new UserResource($toUser));
                // $data['to_id'] = $toUser->id;
                // $tokens = $user->tokens()->where('last_used_at','>',now()->subDays(2))->get();//给自己其他设备发
                // foreach($tokens as $token){
                //     if($token->id!=$currentAccessToken->id){
                //         $gateway->sendToUid($token->id,$data);
                //     }
                // }
                // if($data['to']['id']!=$user->id){//给对方发
                //     $otherUser = User::where('id', $data['to']['id'])->firstOrFail();
                //     $otherTokens = $otherUser->tokens()->where('last_used_at','>', now()->subDays(2))->get();//给对方发
                //     foreach($otherTokens as $otherToken){
                //         $gateway->sendToUid($otherToken->id,$data);
                //     }
                // }
                break;
        }
        return response()->json(['code'=>0,'msg'=>'','data'=>['message'=>$data]]);
    }
}
