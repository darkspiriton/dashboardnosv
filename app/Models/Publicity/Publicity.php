<?php

namespace Dashboard\Models\Publicity;

use Dashboard\Models\Experimental\Product;
use Illuminate\Database\Eloquent\Model;


class Publicity extends Model
{
    protected $table='publicities';
    public $timestamps=false;


    public function socials(){
        return $this->hasMany(Social::class);
    }

    public function processes(){
        return $this->hasMany(Process::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
