<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;


    protected $primaryKey = 'id';
    protected $table = 'orden_detalles';
    protected $connection = 'aguila';

    protected $guarded = [];

}
