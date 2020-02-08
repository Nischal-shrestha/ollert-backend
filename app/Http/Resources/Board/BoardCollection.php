<?php

namespace App\Http\Resources\Board;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BoardCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'  =>  $this->collection->transform(function(Board $board) {
                return [
                    'id' => $board->id,
                    'name'  => $board->name,
                    'description'   => $board->description,
                    'background'    =>  $board->background,
                    'visibility'    =>  $board->visibility,
                    'created_at'    =>  $board->created_at,
                    'updated_at'    =>  $board->updated_at,
                ];
            }),
            'links' =>  array(
                'self'  =>  $request->getUri()
            ),
        ];
    }
}
