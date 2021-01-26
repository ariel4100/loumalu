<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIntertrade extends Model
{
    use HasFactory;


    protected $primaryKey = 'id';
    protected $table = 'ordenes';
    protected $connection = 'aguila';

    public function client() {
        return $this->setConnection('mysql')->belongsTo(Client::class,'cliente_id');

//        return $this->belongsTo(Client::class,'cliente_id');
    }
    public function order() {
        return $this->hasMany(OrderProduct::class,'orden_id');
    }

}
