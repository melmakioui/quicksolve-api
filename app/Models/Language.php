<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'language';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'language'
    ];

    public function incidenceStateLanguages()
    {
        return $this->hasMany(IncidenceStateLanguage::class, 'language_id', 'id');
    }

    public function advantageLanguages()
    {
        return $this->hasMany(AdvantageLanguage::class, 'language_id', 'id');
    }


    public function departmentLanguages()
    {
        return $this->hasMany(DepartmentLanguage::class, 'language_id', 'id');
    }


    public function spaceLanguages()
    {
        return $this->hasMany(SpaceLanguage::class, 'language_id', 'id');
    }
}
