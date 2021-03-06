<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email'=>$this->email,
            'avatar'=>$this->avatar,
            'token_length'=>$this->whenLoaded('tokens',function(){
                return $this->tokens->count();
            }),

            'divide_groups' =>DivideGroupResource::collection($this->whenLoaded('divideGroups')),
            'groups' =>GroupResource::collection($this->whenLoaded('groups')),
            'friend_groups' =>GroupResource::collection($this->whenLoaded('friendGroups')),
        ];
        return parent::toArray($request);
    }
}
