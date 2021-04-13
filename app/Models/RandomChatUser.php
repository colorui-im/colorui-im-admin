<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomChatUser extends Model
{
    use HasFactory;

    //有始有终 加入-等待聊天-聊天中-完成

    // const STATUS_ZERO = 'zero';//初始状态
    const STATUS_JOINING = 'joining';//正在加入

    const STATUS_WAITING = 'waiting';//等待开始
    const STATUS_CHATING = 'chating';//正在聊天
    const STATUS_APPLYING = 'applying';//申请加入

    public static $statusMaps = [
        self::STATUS_JOINING => '加入',
        self::STATUS_WAITING => '等待开始',
        self::STATUS_CHATING => '已完成',
        self::STATUS_APPLYING => '申请加入',
    ];

    const TYPE_0 = 0;
    const TYPE_1 = 1;
    const TYPE_2 = 2;

    public static $TypeMaps = [
        self::TYPE_0 => '主动加入的用户',
        self::TYPE_1 => '申请加入的用户',
        self::TYPE_2 => '拉入的用户',
    ];

}
