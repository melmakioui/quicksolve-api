<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'is_active' => $this->is_active,
            'email' => $this->email,
            'expiration_date' => $this->service_expiration ? date('d/m/Y', strtotime($this->service_expiration)) : '',
            'type' => $this->type,
            'user_data' => $this->userData,
            'department' => DepartmentResource::make($this->department),
            'service' => $this->service,
        ];
    }
}
