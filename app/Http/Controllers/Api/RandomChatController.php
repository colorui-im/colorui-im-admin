<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RandomChatRequest;
use App\Models\RandomChat;
use App\Models\RandomChatUser;

//随机兴趣小组
class RandomChatController extends Controller
{
    //


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

        if(in_array($randomChatUser->status,[RandomChatUser::STATUS_JOINING,RandomChatUser::STATUS_WAITING,RandomChatUser::STATUS_STARTING])){//加过了
            return response()->json(['code'=>1,'msg'=>'您已加入过','data'=>[]]);
        }

        $randomChat = RandomChat::findOrFail($randomChatId);

        $randomChatUser = new RandomChatUser();
        $randomChatUser->random_chat_id = $randomChat->id;
        $randomChatUser->user_id = $user->id;
        $randomChatUser->group_id = $randomChat->group_id;
        $randomChatUser->status = RandomChatUser::STATUS_JOINING;
        $randomChatUser->save();


        return response()->json(['code'=>0,'msg'=>'','data'=>['random_chat_user'=>$randomChatUser]]);
        

    }
}
