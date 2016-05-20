<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="auxproducts";
    
    public function size(){
        return $this->belongsTo(Size::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function alarm(){
        return $this->belongsTo(Alarm::class)->select('day','count');
    }

    public function movements(){
        return $this->hasMany(Movement::class)->select('status','date_shipment')->orderby('created_at','desc');
    }

    public function types(){
        return $this->belongsToMany(Type::class,"types_auxproducts","product_id");
    }
}
