<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIncidence extends Model
{
    use HasFactory;

    protected $table = 'user_incidence';

    public $timestamps = false;
    protected $primaryKey = 'id';


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userTech()
    {
        return $this->belongsTo(User::class, 'tech_id', 'id');
    }

    public function incidence()
    {
        return $this->belongsTo(Incidence::class, 'incidence_id', 'id');
    }

}
