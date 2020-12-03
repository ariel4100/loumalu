<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $appends = ['date'];

    protected $fillable = [
        'title', 'text','order','slug','image','galley'
    ];

    protected $casts = [
        'galley' => 'array',
    ];

    public $translatable = [
        'title',
        'text',
        'slug',
    ];

    public function getDateAttribute()
    {

        return $this->created_at->format('d-m-Y');

    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
