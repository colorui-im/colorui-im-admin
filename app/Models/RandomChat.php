<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RandomChat extends Model
{
    use HasFactory,softDeletes;

    protected $casts = [
        'extra' => 'json'
    ];

    //有始有终 加入-等待开始-聊天中
    const STATUS_JOINING = 'joining';//正在加入
    const STATUS_WAITING = 'waiting';//等待开始()
    const STATUS_CHATING = 'chating';//聊天中

    public static $statusMaps = [
        self::STATUS_JOINING => '加入',
        self::STATUS_CHATING => '已完成',
    ];


    const TYPE_0 = 0;


    public static $extra = [
        self::TYPE_0=>[
            'count'=>5//限制兴趣小组人数
        ]
    ];



    public function users()
    {
        return $this->belongsToMany(User::class, 'random_chat_users', 'random_chat_id', 'user_id');
    }
}
