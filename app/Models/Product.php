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


 
    public function family() {
        return $this->belongsTo(Family::class);
    }

    public function scopeFamilia($query, $type)
    {
        return $query->where('family_id',$type);
    }
    public function scopeMarca($query, $type)
    {
        return $query->where('marca',$type);
    }
    public function related() {
        return $this->belongsToMany(Product::class,'related_products','product_id','related_id');
    }

    public function industrias()
    {
        return $this->belongsToMany(Block::class, 'product_blocks','product_id','block_id');
    }
    public function getMagicCodeAttribute() {
        if(str_contains($this->title, 'STD')){
            
            $parsed = substr($this->slug, 0, strpos($this->slug, 'std'));
            
            return Product::select('id', 'code', 'title','marca')->where('slug', 'LIKE', $parsed.'%')->where('slug', '!=', $this->slug)->get();
        }else{
            return [];
        }
        
    }
    
    public function getCheckSubprodAttribute() {
        if(!str_contains($this->title, 'STD')){
            
            $parsed = substr($this->slug, 0, -4);

            $check = Product::where('slug', 'LIKE', $parsed.'%')->where('slug', 'LIKE', '%-std%')->orWhere('slug', 'LIKE', '%-std-lle%')->first();
            
            if($check){
            return true;    
            }else{
            return false;
            }
            
            
            
        }else{
            return false;
        }
        
    }
}
