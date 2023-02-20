<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advantage extends Model
{
    use HasFactory;

    protected $table = 'advantage';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function advantageLangs()
    {
        return $this->hasMany(AdvantageLanguage::class, 'advantage_id', 'id');
    }
}
