<?php

namespace App\Http\Resources;

use Carbon\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class WebPageResource extends JsonResource
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
            'key' => $this->keyy,
            'value' => $this->webPageLanguages->where('language_id', 2)->first()
        ];
    }
}
