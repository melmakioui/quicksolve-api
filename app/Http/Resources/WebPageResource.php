<?php

namespace App\Http\Resources;

use Carbon\Language;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\WebPageLanguage;

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

        $webPageLangs = $this->webPageLanguages()
            ->where('language_id', 1)->first();    

        return [
            'id'=> $this->id,
            'key'=> $this->keyy,
            'name'=> $webPageLangs->content ?? '',
            'language_id'=> $webPageLangs->language_id ?? '',
            'webpage_id'=> $webPageLangs->webpage_id ?? '',
        ];
    }
}
