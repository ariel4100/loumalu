<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'text','order','slug','description','image','video','file','padre_id','family_id','id'
    ];


    public function productos() {
        return $this->hasMany(Product::class);
    }

    public function padre()
    {
        return $this->belongsTo(Family::class,'padre_id');
    }

    public function childFamilies()
    {
        return $this->hasMany(Family::class,'padre_id')->with('childFamilies');
    }

    public function remove_hijos($item){

        if (count($item->childFamilies) > 0){
            foreach ($item->childFamilies as $value){
                $item->childFamilies()->delete();
                $value->remove_hijos($value);
            }
//            dd($item->childFamilies);
//            $item->childFamilies()->delete();
        }else{
            return 1;
//            $item->childFamilies()->delete();
        }
    }
}
