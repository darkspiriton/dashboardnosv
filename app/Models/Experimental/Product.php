<?php

namespace Dashboard\Models\Experimental;

use Dashboard\Models\Kardex\Movements;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="auxproducts";
    
    public function size(){
        return $this->belongsTo(Size::class)->select('name');
    }

    public function color(){
        return $this->belongsTo(Color::class)->select('name');
    }

    public function provider(){
        return $this->belongsTo(Provider::class)->select('name');
    }

    public function alarm(){
        return $this->belongsTo(Alarm::class)->select('day','count');
    }

    public function movements(){
        return $this->hasMany(Movements::class);
    }
}
