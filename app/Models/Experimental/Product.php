<?php

namespace Dashboard\Models\Experimental;

use Dashboard\Models\Publicity\Publicity;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "auxproducts";
    protected $hidden = ['pivot', 'updated_at'];
    protected $casts = ["utility" => "float", "cost_provider" => "float"];

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
        return $this->belongsTo(Alarm::class)->select('id','day','count');
    }

    public function movements(){
        return $this->hasMany(Movement::class)->orderby('created_at','desc');
    }

    public function bymovements(){
        return $this->hasMany(Movement::class)->where('status','salida')->orderby('created_at','desc');
    }

    public function types(){
        return $this->belongsToMany(Type::class,"types_auxproducts","product_id");
    }

    public function outfits(){
        return $this->belongsToMany(Outfit::class,"products_outfits","product_id");
    }

    public function settlement(){
        return $this->hasOne(Settlement::class);
    }

    public function outfit_movement(){
        return $this->belongsToMany(MovementOutFit::class,'aux_outfit_movements_detail','product_id');
    }
    
    public function publicities(){
        return $this->hasMany(Publicity::class);
    }
}
