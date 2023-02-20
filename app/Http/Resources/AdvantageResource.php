<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvantageResource extends JsonResource
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
            'names' => $this->advantageLangs->map(function ($advantageLang) {
                return [
                    'language' => $advantageLang->language->name,
                    'name' => $advantageLang->name,
                ];
            }),
        ];
    }


}
