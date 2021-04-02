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
        $friendGroups = $user->friendGroups()->get();
        \Log::info('start Group',[
            
            ]);
        foreach ($groups as $group){//大群
            \Log::info('group',[
                'user_id'=>$user->id,
                'group_id'=>$group->id,
            ]);
            $gateway->joinGroup($request->client_id,$group->id);
        }
        \Log::info('start friendGroup',[
            
        ]);
        foreach ($friendGroups as $friendGroup){//和好友的群,只有两人
            \Log::info('friendGroup',[
                'user_id'=>$user->id,
                'group_id'=>$friendGroup->id,
            ]);
            $gateway->joinGroup($request->client_id,$friendGroup->id);
        }

        return response()->json(['code'=>0,'msg'=>'','data'=>[]]);
    }

    //不用登录就可在大群里聊
    public function bindGroupUid(ImRequest $request)
    {


        // dd($user);
        // return ['code'=>0,'msg'=>'ok','data'=>['token'=>$user->currentAccessToken()->tokenable]];


        $gateway = app('gateway');

        \Log::info('url:'.$request->url());
        if(str_contains($request->url(),'service')){//客服
            foreach (range(1,10) as $id){
                $gateway->joinGroup($request->client_id,$id);//绑定到10个群里，相当于和10个用户聊天
            }

        }else{
            $gateway->joinGroup($request->client_id,1);//默认绑定到大群里

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
                \Log::info('group_id:'.$data['to']['id']);
                app('gateway')->sendToGroup((int)$data['to']['id'],$data,$currentClientId);

                break;
            case 'friend':
                unset($data['client_id']);
                $toGroup = Group::findOrFail($data['to']['id']);
                $data['to_id'] = $toGroup->id;
                \Log::info('group_id:'.$data['to']['id']);

                app('gateway')->sendToGroup((int)$data['to']['id'],$data,$currentClientId);
                break;



        }
        return response()->json(['code'=>0,'msg'=>'','data'=>['message'=>$data]]);
    }

    //不用登录就能发送信息
    public function groupSend(ImRequest $request)
    {


        $data = $request->validationData();


        $data['sended_at'] = now()->format('Y-m-d H:i:s');

        $currentClientId = $data['client_id'];

        switch($data['type']){
            case 'group':
                unset($data['client_id']);
                
                \Log::info('group_id:'.$data['to']['id']);
                app('gateway')->sendToGroup((int)$data['to']['id'],$data,$currentClientId);
                // $gateway->sendToUid($currentAccessToken->id,$data);
                break;
            case 'friend':
                unset($data['client_id']);
                app('gateway')->sendToGroup((int)$data['to']['id'],$data,$currentClientId);
                break;


        }
        return response()->json(['code'=>0,'msg'=>'','data'=>['message'=>$data]]);
    }

    public function joinGroup(ImRequest $request)
    {

        $group= Group::findOrFail($request->group_id);

        $joinUserIds = $request->join_user_ids;
        //todo 限制拉自己的好友

        $state = 0; //状态为1 前端需要重新跳到新建的群

        if($group->type==1){//这是个群聊群，直接加入

            $group->users()->syncWithoutDetaching($joinUserIds);

        }elseif($group->type==0){//这是"好友"的群，需重新新建个群，把好友和新的一起加入进去

            $newGroup = Group::factory()->create();
            $newGroup->type = 1;
            $newGroup->save();

            $userIds = $group->users()->get(['id'])->pluck('id')->toArray();

            $userIds = array_merge($userIds, $joinUserIds);
            $newGroup->users()->sync($userIds);
            $state = 1;

            $group = $newGroup;
        }

        //todo 给加群的用户发送一条系统通知，让对方重新绑定下wbsocket，可以接收信息

        //可放在事件中
        $gateway = app('gateway');
        $currentAccessToken = $request->user()->currentAccessToken();
        $users = $group->users()->with(['tokens'=>function($query){
            $query->where('last_used_at', '>', now()->subMinutes(60*24));//一天之内的才推送
        }])->get();
        foreach ($users as $user){
            foreach ($user->tokens as $token){
                // if($currentAccessToken->id!=$token->id){//都需要重新绑定
                    $gateway->sendToUid($token->id,['type'=>'system_join_group']);
                // }
            }
        }


        
        return response()->json(['code'=>0,'msg'=>'','data'=>['state'=>$state ,'group'=>new GroupResource($group)]]);

    }
}
