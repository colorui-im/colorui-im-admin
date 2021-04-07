<?php

namespace App\Listeners;

use App\Events\ImSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Message;

class SaveMessage
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
     * @param  ImSend  $event
     * @return void
     */
    public function handle(ImSend $event)
    {
        $data = $event->data;

        Message::createMessage($data);

        
    }
}
