<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceState extends Model
{
    use HasFactory;

    protected $table = 'incidence_state';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function incidenceStateLangs()
    {
        return $this->hasMany(IncidenceStateLanguage::class, 'incidence_state_id', 'id');
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class, 'incidence_state_id', 'id');
    }
}
