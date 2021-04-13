<?php

namespace App\Listeners;

use App\Events\RandomChatApply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\RandomChat;
use App\Models\RandomChatUser;
use App\Models\Group;
use App\Http\Resources\RandomChatResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\GroupResource;

class NotificationGroupRandomChatUserJoined
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RandomChatApply  $event
     * @return void
     */
    public function handle(RandomChatApply $event)
    {
        $randomChatId = $event->randomChatId;
        $randomChat = RandomChat::findOrFail($randomChatId);
        $users = $randomChat->users()->get();
        $randomChat->load(['users']);

        // $data = [
        //     'type' => 'random_chat_apply',//申请加入             
        //      'content' => [
        //          'group_id'=> $randomChat->group_id
        //      ]
        // ];

        $toGroup = Group::findOrFail($randomChat->child_group_id);
        $needAgressUserCount = (int)($randomChat->users()->wherePivot('status',RandomChatUser::STATUS_CHATING)->count()/2);//需要一半用户同意
        $haiNeedAgreeUserCount = $needAgressUserCount;//还需要多少用户同意
        $data = [
            'unique_slug'=> $toGroup->id.'-'.(now()->getTimestamp()).'-'.uniqid(),//刷新聊天记录使用
            'type' =>'group',
            'from' => new UserResource($event->user),
            'from_id' => $event->user->id,
            'to' => (new GroupResource($toGroup)),
            'to_id' => $toGroup->id,
            'message_type' => 'random-chat-apply',//申请加入消息类型
            'data' =>[
                'content'=>'需要'.$needAgressUserCount.'人同意，目前已有'.'0人同意'.',还需'.$haiNeedAgreeUserCount.'人',
                'msg'=>$event->user->name.':申请加入该小组',
                'extra' => [
                    'need_agree_users_count' => $needAgressUserCount,//
                    'agree_users'=>[],//记录已同意的用户
                    'refuse_users'=>[],//记录不同意的用户
                    'waiver_users'=>[],//记录弃权的用户
                    'apply_random_chat_id' => $event->randomChatId,
                    'apply_user_id' => $event->user->id,
                    'vote_user_ids' => [],
                    'can_vote_user_ids' => $randomChat->users()->wherePivot('status',RandomChatUser::STATUS_CHATING)->get()->pluck('id')->toArray(),//当前时间点的用户才可以投票
                    'if_pass'=> 0
                ]
            ],
            'sended_at' =>date('Y-m-d H:i:s')

        ];

        if(config('im.save_message')){//是否支持保存数据到数据库，默认true
            event(new \App\Events\ImSend($data));
        }

        app('gateway')->sendToGroup($randomChat->group_id,[
            'type' => 'random_chat_joining',
            'content' => new RandomChatResource($randomChat)
        ]);//通知加了多少人

        app('gateway')->sendToGroup($randomChat->child_group_id,$data);

    }
}
