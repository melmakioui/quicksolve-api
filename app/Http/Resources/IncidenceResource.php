<?php

namespace App\Http\Resources;

use App\Models\IncidenceStateLanguage;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->userIncidences->first()->user ? $this->userIncidences->first()->user : null;
        $tech = $this->userIncidences->last()->userTech ? $this->userIncidences->last()->userTech : null;
        $dateStart = new \DateTime($this->date_start);
        $dateEnd = new \DateTime($this->date_end);
        $state = IncidenceStateLanguage::where('incidence_state_id', $this->incidence_state_id)
            ->where('language_id', 1)->first();
            
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'email'=> $this->email ? $this->email : '',
            'space' => new SpaceResource($this->space),
            'department' => new DepartmentResource($this->department),
            'incidenceState' => new IncidenceStateResource($state),
            'user' => $user ? new UserResource($user) : '',
            'tech' => $tech ? new UserResource($tech) : '',
            'date_start' => $this->date_start ? date('d/m/Y', strtotime($this->date_start)) : '',
            'date_end' => $this->date_end ? date('d/m/Y', strtotime($this->date_end)) : '',
            'date_period' => ($this->incidence_state_id == 3 ? $dateEnd->diff($dateStart)->days : '') == 0 ? 1 : $dateEnd->diff($dateStart)->days,       
        ];


    }

}
