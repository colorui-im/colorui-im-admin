<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\RandomChatUser;

class RandomChatUserResource extends JsonResource
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
            'id'=>$this->id,
            'name'=>$this->name,
            'avatar'=>$this->avatar,
            'status' =>$this->pivot->status,
            'status_map' =>RandomChatUser::$statusMaps[$this->pivot->status],
        ];
        return parent::toArray($request);
    }
}
