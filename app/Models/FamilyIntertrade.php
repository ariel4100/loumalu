<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyIntertrade extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $connection = 'aguila';
    public $timestamps = false;

//    public function productos()
//    {
//        return $this->setConnection('mysql')->hasMany(ProductIntertrade::class,'categoria_id');
////        return $this->setConnection('mysql')->hasMany(Product::class,'mlproducto_id');
//    }

}
