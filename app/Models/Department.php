<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'type'
    ];
    public function incidences()
    {
        return $this->hasMany(Incidence::class, 'department_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    public function departmentLangs()
    {
        return $this->hasMany(DepartmentLanguage::class, 'department_id', 'id');
    }

}
