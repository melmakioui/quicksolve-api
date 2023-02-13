<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidence extends Model
{
   
    protected $table = 'incidence';
    public $timestamps = false;

    protected $primaryKey = 'id';
    
    
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function incidenceState()
    {
        return $this->belongsTo(IncidenceState::class, 'incidence_state_id', 'id');
    }

    public function incidenceFiles()
    {
        return $this->hasMany(IncidenceFile::class, 'incidence_id', 'id');
    }



}
