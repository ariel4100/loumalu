<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIntertrade extends Model
{
    use HasFactory;

    protected $primaryKey = 'IdMlProducto';
    protected $table = 'mlproductosintertrade';
    protected $connection = 'aguila';
    public $timestamps = false;

    public function product()
    {
        return $this->setConnection('mysql')->hasOne(Product::class,'mlproducto_id','IdMlProducto');
    }



}
