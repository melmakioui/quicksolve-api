<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'username',
        'is_active',
        'email',
        'service_expiration',
        'type',
        'department_id',
        'service_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id', 'id');
    }

    public function userData(){
        return $this->hasOne(UserData::class, 'user_id', 'id');
    }

    public function userIncidences()
    {
        return $this->hasMany(UserIncidence::class, 'user_id', 'id');
    }
}
