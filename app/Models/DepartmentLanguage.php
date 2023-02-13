<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentLanguage extends Model
{
    use HasFactory;

    protected $table = 'department_language';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
