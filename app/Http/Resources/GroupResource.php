<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'type'=>$this->type,
            'user_to_user'=>$this->user_to_user,
            'users'=>UserResource::collection($this->whenLoaded('users'))
        ];
        return parent::toArray($request);
    }
}
