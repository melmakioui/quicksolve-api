<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceFiles extends Model
{
    use HasFactory;

    protected $table = 'incidence_files';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function incidence()
    {
        return $this->belongsTo(Incidence::class, 'incidence_id', 'id');
    }
}
