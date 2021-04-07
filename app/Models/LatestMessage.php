<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LatestMessage extends Model
{
    use HasFactory,softDeletes;

    public static function createLatestMessage($message)
    {

        $latestMessage = static::query()->where('group_id', $message->to_id)->first();
        if(!$latestMessage){
            $latestMessage = new static();
            $latestMessage->group_id = $message->to_id;
        }
        $latestMessage->type = $message->type;
        $latestMessage->message_id = $message->id;
        $latestMessage->sended_at = $message->sended_at;
        $latestMessage->save();

    }
}
