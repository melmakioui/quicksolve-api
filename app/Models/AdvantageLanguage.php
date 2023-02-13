<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvantageLanguage extends Model
{
    use HasFactory;

    protected $table = 'advantage_language';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function advantage()
    {
        return $this->belongsTo(Advantage::class, 'advantage_id', 'id');
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
