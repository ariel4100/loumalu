<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title', 'text','description','order','slug','section','image','image_2','image_3'
    ];

    public $translatable = [
        'title',
        'description',
        'text',
        'slug',
    ];


    public function news() {
        return $this->hasMany('App\Models\News');
    }
}
