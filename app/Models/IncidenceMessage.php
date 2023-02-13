<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidenceMessage extends Model
{
    use HasFactory;

    protected $table = 'incidence_message';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function incidence()
    {
        return $this->belongsTo(Incidence::class, 'incidence_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
