<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;


    protected $casts = [
        'gallery' => 'array'
    ];

    protected $fillable = [
        'title', 'text','order','slug','video','image','description','file','family_id','text_video','gallery','mlproducto_id','id'
    ];



    public function product_intertrade()
    {
        return $this->setConnection('aguila')->belongsTo(ProductIntertrade::class,'mlproducto_id','id');
    }

    public function family() {
        return $this->belongsTo(Family::class);
    }



    public function related() {
        return $this->belongsToMany(Product::class,'related_products','product_id','related_id');
    }

    public function industrias()
    {
        return $this->belongsToMany(Block::class, 'product_blocks','product_id','block_id');
    }

}
