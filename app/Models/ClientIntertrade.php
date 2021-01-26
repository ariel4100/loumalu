<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientIntertrade extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $connection = 'aguila';
    public $timestamps = false;

}
