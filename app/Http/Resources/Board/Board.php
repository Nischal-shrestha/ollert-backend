<?php

namespace App\Http\Resources\Board;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\User as UserResource;

class Board extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'description'   =>  $this->description,
            'background'    =>  $this->background,
            'visibility'    =>  $this->visibility,
            'created_at'    =>  $this->created_at,
            'updated_at'    =>  $this->updated_at,
            'owner' =>  new UserResource($this->owner)
        ];
    }
}
