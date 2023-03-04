<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceStateLanguage extends Model
{
    use HasFactory;

    protected $table = 'incidence_state_language';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'incidence_state_id',
        'language_id',
        'status_name',
        'name',
    ];
    public function incidenceState()
    {
        return $this->belongsTo(IncidenceState::class, 'incidence_state_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
