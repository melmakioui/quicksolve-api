<?php

namespace App\Http\Resources;

use App\Http\Resources\AdvantageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
        'name' => $this->name,
        'price' => $this->price,
        'tax' => $this->tax,
        'advantages' => $this->advantages->map(function ($advantage) {
            return AdvantageResource::make($advantage);
        }),
    ];
    }
}
