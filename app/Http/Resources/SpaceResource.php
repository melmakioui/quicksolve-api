<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->spaceLangs->where('language_id', 1)->first()->name,
            'traductions' => $this->spaceLangs->map(function($item, $key){
                return [
                    $item->language->name => $item->name,
                ];
            }),
        ];
    }
}
