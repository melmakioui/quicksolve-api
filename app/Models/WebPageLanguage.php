<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebPageLanguage extends Model
{
    use HasFactory;

    protected $table = 'web_page_language';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function webPage()
    {
        return $this->belongsTo(WebPage::class, 'web_page_id', 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
