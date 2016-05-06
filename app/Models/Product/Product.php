<?php

namespace Dashboard\Models\Product;

use Dashboard\Models\Interest\Detail;
use Dashboard\Models\Kardex\Kardex;
use Dashboard\Models\Kardex\Type as TypeKardex;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Scope\Detail as scopeDetail;


class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','created_at','updated_at'
    ];
    
    protected  $table='products';

    public function kardexs(){
        return $this->hasMany(Kardex::class);
    }
    
    public function type(){
        return $this->belongsTo(TypeKardex::class);
    }

    public function typeProduct(){
        return $this->belongsTo(TypeProduct::class);
    }

    public function details(){
        return $this->hasMany(Detail::class);
    }

    public function scopesDetail()
    {
        return $this->hasMany(scopeDetail::class);
    }

}