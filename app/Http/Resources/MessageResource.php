<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'unique_slug' => $this->unique_slug,
            'from_id' => $this->from_id,
            'to_id' => $this->to_id,
            'from' => $this->from,
            'to' => $this->to,
            'type' => $this->type,
            'message_type' => $this->message_type,
            'data' => $this->data,
            'sended_at' => $this->sended_at,
        ];
        return parent::toArray($request);
    }
}
