<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RandomChatRequest;
use App\Models\Message;
use App\Models\User;
use App\Models\Group;
use App\Models\RandomChat;
use App\Models\RandomChatUser;
use App\Http\Resources\RandomChatResource;
use App\Http\Resources\RandomChatUserResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MessageResource;

//随机兴趣小组
class RandomChatController extends Controller
{
    //

    public function byGroupId(RandomChatRequest $request)
    {
        $user = $request->user();
        $groupId = $request->input('group_id');
        $randomChats = RandomChat::where('group_id', $groupId)->with('users')->get();
        $randomChats = RandomChatResource::collection($randomChats);

        return response()->json(['code'=>0,'msg'=>'','data'=>['lists'=>$randomChats]]);
    }

    // //进到群组里初始化，获取用户在当前群里的状态
    // public function init(RandomChatRequest $request)
    // {
    //     $groupId = $request->input('group_id');
    //     $user = $request->user();
    //     $randomChatUser = RandomChatUser::where('group_id',$groupId)->where('user_id',$user->id)->latest('created_at')->first();
    //     if(!$randomChatUser){
    //         $randomChatUser = [
    //             'status'=> RandomChatUser::STATUS_ZERO
    //         ];
    //     }

    //     return response()->json(['code'=>0,'msg'=>'','data'=>['random_chat_user'=>$randomChatUser]]);
        
    // }

    public function join(RandomChatRequest $request)
    {
        $randomChatId = $request->input('random_chat_id');
        $user = $request->user();

        $randomChatUser = RandomChatUser::where('random_chat_id',$randomChatId)->where('user_id',$user->id)->first();

        if($randomChatUser){//用户 STATUS_WAITING STATUS_CHATING ，STATUS_JOINING是该兴趣组的初始状态
            if(in_array($randomChatUser->status,[RandomChatUser::STATUS_JOINING,RandomChatUser::STATUS_WAITING,RandomChat::STATUS_CHATING])){//加过了
                return response()->json(['code'=>1,'msg'=>'您已加入过','data'=>[]]);
            }
        }
      

        $randomChat = RandomChat::findOrFail($randomChatId);

        $randomChatUser = new RandomChatUser();
        $randomChatUser->random_chat_id = $randomChat->id;
        $randomChatUser->user_id = $user->id;
        $randomChatUser->group_id = $randomChat->group_id;
        $randomChatUser->status = RandomChatUser::STATUS_WAITING;
        $randomChatUser->save();


        event(new \App\Events\RandomChatJoining($randomChat->id));

        return response()->json(['code'=>0,'msg'=>'加入成功','data'=>['random_chat_user'=>$randomChatUser]]);
        

    }

    //兴趣小组已经加满后，新的成员申请加入
    public function apply(RandomChatRequest $request)
    {
        $randomChatId = $request->input('random_chat_id');
        $user = $request->user();

        $randomChatUser = RandomChatUser::where('random_chat_id',$randomChatId)->where('user_id',$user->id)->first();

        if($randomChatUser){//用户 STATUS_WAITING STATUS_CHATING ，STATUS_JOINING是该兴趣组的初始状态

            if($randomChatUser->status == RandomChatUser::STATUS_APPLYING){
                return response()->json(['code'=>1,'msg'=>'您已申请过','data'=>[]]);
            }
            if(in_array($randomChatUser->status,[RandomChatUser::STATUS_JOINING,RandomChatUser::STATUS_WAITING,RandomChat::STATUS_CHATING,RandomChatUser::STATUS_APPLY])){//加过了
                return response()->json(['code'=>1,'msg'=>'您已加入过','data'=>[]]);
            }
        }
        $randomChat = RandomChat::findOrFail($randomChatId);
        $randomChatUser = new RandomChatUser();
        $randomChatUser->random_chat_id = $randomChat->id;
        $randomChatUser->user_id = $user->id;
        $randomChatUser->type = RandomChatUser::TYPE_1;
        $randomChatUser->group_id = $randomChat->group_id;
        $randomChatUser->status = RandomChatUser::STATUS_APPLYING;
        $randomChatUser->save();

        
        event(new \App\Events\RandomChatApply($randomChat->id,$user));

        return response()->json(['code'=>0,'msg'=>'申请成功','data'=>['random_chat_user'=>$randomChatUser]]);

    }

    //兴趣小组里的成员审核
    public function vote(RandomChatRequest $request)
    {
        $message = $request->message;
        $type = $request->type;//agree refuse waiver

        $modelMessage = Message::where('unique_slug', $message['unique_slug'])->first();

        if(!$modelMessage){
            return response()->json(['code'=>1,'msg'=>'请在右上角，兴趣小组里模拟','data'=>[]]);
        }

        if($modelMessage->message_type!='random-chat-apply'){
            return response()->json(['code'=>1,'msg'=>'消息类型不正确','data'=>[]]);
        }

        $user = $request->user();

        $group = Group::findOrFail($modelMessage->to_id);

        if(!$group->users()->where('id',$user->id)->exists()){
            return response()->json(['code'=>1,'msg'=>'您没有权限！！','data'=>[]]);
        }


        $data = $modelMessage->data;


        if(!in_array($user->id,$data['extra']['can_vote_user_ids'])){
            return response()->json(['code'=>1,'msg'=>'您没有权限！！!','data'=>[]]);
        }

        if(in_array($user->id,$data['extra']['vote_user_ids'])){
            return response()->json(['code'=>1,'msg'=>'您已投过票','data'=>[]]);
        }

        switch($type){
            case 'agree':
                $data['extra']['agree_users'][] = new UserResource($user);
                break;
            case 'refuse':
                $data['extra']['refuse_users'][] = new UserResource($user);
                break;
            case 'waiver':
                $data['extra']['waiver_users'][] = new UserResource($user);
                break;
        }
        $data['extra']['vote_user_ids'][] = $user->id;
        $data['content'] ='需要'.$data['extra']['need_agree_users_count'].'人同意，目前已有'.count($data['extra']['agree_users']).'人同意';
        if($data['extra']['need_agree_users_count']>count($data['extra']['agree_users'])){
            $data['content'].=',还需'.($data['extra']['need_agree_users_count']-count($data['extra']['agree_users'])).'人';
        }
        $modelMessage->data = $data;

        $modelMessage->save();


        if(count($data['extra']['agree_users'])==$data['extra']['need_agree_users_count']){//达到加入条件
            $data['extra']['if_pass'] = 1;
            $modelMessage->data = $data;
            $modelMessage->save();
            $applyUser = User::findOrFail($data['extra']['apply_user_id']);
            $group->users()->syncWithoutDetaching([$applyUser->id]);
            $randomChatUser = RandomChatUser::where('random_chat_id', $data['extra']['apply_random_chat_id'])->where('user_id', $applyUser->id)->firstOrFail();
            $randomChatUser->status = RandomChatUser::STATUS_CHATING;
            $randomChatUser->save();

            //给申请用户发送信息
            $gateway = app('gateway');
            $users = User::with(['tokens'=>function($query){
                $query->where('last_used_at', '>', now()->subMinutes(60*24));//一天之内的才推送
            }])->where('id',$applyUser->id)->get();
            foreach ($users as $user){
                foreach ($user->tokens as $token){
                    $gateway->sendToUid($token->id,['type'=>'random_chat_vote_agree']);
                }
            }

            //更新小组信息
            $randomChat = RandomChat::findOrFail($data['extra']['apply_random_chat_id']);
            $randomChat->load('users');
            $sendData = [
                'type' => 'random_chat_joining',
                'content' => new RandomChatResource($randomChat)
            ];
            app('gateway')->sendToGroup($randomChat->group_id,$sendData);//通知新加用户

        }

        //更新投票信息
        $sendData = [
            'type'=>'random_chat_vote',
            'content' => new MessageResource($modelMessage)
        ];

        app('gateway')->sendToGroup($group->id,$sendData);


        return response()->json(['code'=>0,'msg'=>'投票成功']);
        

    }
}
