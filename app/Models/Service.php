<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'service';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function advantages()
    {
        return $this->hasMany(Advantage::class, 'service_id', 'id');
    }
}
