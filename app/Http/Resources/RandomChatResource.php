<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RandomChat;

class RandomChatResource extends JsonResource
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
            'name' => $this->name,
            'group_id' => $this->group_id,
            'child_group_id' => $this->child_group_id,
            'status' => $this->status,
            'status_map' => RandomChat::$statusMaps[$this->status],
            'all_count' => $this->extra['count'],
            'users_count' => $this->whenLoaded('users',function(){
                return $this->users->count();
            }),
        ];
        return parent::toArray($request);
    }
}
