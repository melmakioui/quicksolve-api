<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Incidence extends Model
{
   
    protected $table = 'incidence';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'space_id',
        'department_id',
        'incidence_state_id',
        'title',
        'email',
        'description',
        'date_start',
        'date_end',
        'date_period',
    ];
    
    protected $appends = [
        'date_period'
    ];

    public function getDatePeriodAttribute()
    {
        if ($this->date_start && $this->date_end) {
            $dateStart = Carbon::parse($this->date_start);
            $dateEnd = Carbon::parse($this->date_end);
            $datePeriod = $dateStart->diffInDays($dateEnd);
            return $datePeriod;
        }
        return null;
    }


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

    public function userIncidences()
    {
        return $this->hasMany(UserIncidence::class, 'incidence_id', 'id');
    }



}
