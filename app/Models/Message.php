<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory,SoftDeletes;

    protected $casts = [
        'from' =>'json',
        'to' =>'json',
        'data' =>'json',
    ];

    public static function createMessage($data)
    {
        $message = new static();
        $message->from_id = $data['from_id'];
        $message->from = $data['from'];
        $message->to_id = $data['to_id'];
        $message->to = $data['to'];
        $message->type = $data['type'];
        $message->message_type = $data['message_type'];
        $message->data = $data['data'];
        $message->sended_at = date('Y-m-d H:i:s');
        $message->save();

        LatestMessage::createLatestMessage($message);

        
    }
}
