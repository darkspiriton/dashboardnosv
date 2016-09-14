<?php

namespace Dashboard\Models\Experimental;

use Dashboard\Models\Publicity\Publicity;
use Dashboard\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    protected $table = "auxproducts";
    protected $hidden = ['pivot', 'updated_at','deleted_at'];
    protected $casts = ["utility" => "float", "cost_provider" => "float"];
    protected $dates = ['deleted_at'];
    protected $appends = ["final_price"];

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

    public function movement(){
        return $this->hasOne(Movement::class)->orderby('created_at','desc');
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

    public function user(){
        return $this->belongsTo(User::class)->select("id","first_name");
    }

    public function detail_statuses(){
        return $this->hasMany(ProductDetailStatus::class);
    }
    
    public function getFinalPriceAttribute(){
        return $this->attributes["cost_provider"] +  $this->attributes["utility"];
    }
}
