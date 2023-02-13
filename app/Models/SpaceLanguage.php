<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaceLanguage extends Model
{
    use HasFactory;

    protected $table = 'space_language';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

}
