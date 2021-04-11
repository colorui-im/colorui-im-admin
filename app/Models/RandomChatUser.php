<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomChatUser extends Model
{
    use HasFactory;

    //有始有终 加入-等待聊天-聊天中-完成

    const STATUS_ZERO = 'zero';//初始状态
    const STATUS_JOINING = 'joining';//正在加入
    const STATUS_WAITING = 'waiting';//等待开始
    const STATUS_CHATING = 'chating';//正在聊天
}
