<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\ImSend' => [
            'App\Listeners\SaveMessage',
        ],
        'App\Events\RandomChatJoining' => [//加入兴趣小组
            'App\Listeners\NotificationGroupRandomChatUser',
        ],
        'App\Events\RandomChatApply' => [//兴趣小组满了之后，申请加入兴趣小组，通知兴趣小组的成员，需要兴趣小组成员的一半同意才可以加入
            'App\Listeners\NotificationGroupRandomChatUserJoined',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
