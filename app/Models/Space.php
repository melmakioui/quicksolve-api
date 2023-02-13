<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $table = 'space';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function incidences()
    {
        return $this->hasMany(Incidence::class, 'space_id', 'id');
    }
}
