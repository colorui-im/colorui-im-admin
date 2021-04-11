<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RandomChatRequest;
use App\Models\RandomChat;
use App\Models\RandomChatUser;
use App\Http\Resources\RandomChatResource;
use App\Http\Resources\RandomChatUserResource;

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
}
