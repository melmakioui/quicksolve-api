<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPage extends Model
{
    use HasFactory;

    protected $table = 'web_page';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'keyy',
    ];


    public function webPageLanguages()
    {
        return $this->hasMany(WebPageLanguage::class, 'webpage_id', 'id');
    }

}

