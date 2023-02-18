<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->departmentLangs->where('language_id', 1)->first()->name,
            'traduction' => $this->departmentLangs->where('language_id', 2)->first()->name,
        ];
    }
}
