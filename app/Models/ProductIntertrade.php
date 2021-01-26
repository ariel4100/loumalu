<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIntertrade extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'productos';
    protected $connection = 'aguila';
    public $timestamps = false;

    public function family()
    {
        return $this->setConnection('mysql')->belongsTo(FamilyIntertrade::class,'categoria_id');
    }

    public function related() {
        return  $this->belongsToMany(ProductIntertrade::class,'related_products','product_id','related_id');
    }


}
