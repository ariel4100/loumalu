<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Download extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title', 'text','order','image','video','file',
    ];

    public $translatable = [
        'title',
        'text',
    ];
}
