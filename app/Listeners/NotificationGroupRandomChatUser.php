<?php

namespace App\Listeners;

use App\Events\RandomChatJoining;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\RandomChat;
use App\Models\RandomChatUser;
use App\Models\Group;
use App\Http\Resources\RandomChatResource;

class NotificationGroupRandomChatUser
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
     * @param  RandomChatJoining  $event
     * @return void
     */
    public function handle(RandomChatJoining $event)
    {
        $randomChatId = $event->randomChatId;
        $randomChat = RandomChat::findOrFail($randomChatId);
        $users = $randomChat->users()->get();
        $randomChat->load('users');
        $data = [
            'type' => 'random_chat_joining',
            // 'random_chat_id' => $randomChatId,
            // 'count' => $users->count()
            'content' => new RandomChatResource($randomChat)
        ];




        if($users->count()==$randomChat->extra['count']){//说明加满了
            $group = new Group();
            $group->name = $randomChat->name;
            $group->type = 1;
            $group->save();

            $group->users()->sync($users->pluck('id')->toArray());

            $data = [
                'type' => 'random_chat_waiting',//前端收到事件自动跳转             
                 'content' => new RandomChatResource($randomChat)
            ];
            $randomChat->child_group_id = $group->id;
            $randomChat->status = RandomChat::STATUS_CHATING;
            $randomChat->save();
            $syncData = [];
            foreach ($users as $user){
                $syncData[$user->id] = ['status'=> RandomChatUser::STATUS_CHATING];
            }
            $randomChat->users()->syncWithoutDetaching($syncData);
            $randomChat->load('users');
            app('gateway')->sendToGroup($randomChat->group_id,$data);//通知加满
        }else{
            app('gateway')->sendToGroup($randomChat->group_id,$data);//通知加了多少人
        }




    }
}
