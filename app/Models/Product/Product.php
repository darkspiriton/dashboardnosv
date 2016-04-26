<?php

namespace Dashboard\Models\Product;

use Dashboard\Models\Interest\Detail;
use Dashboard\Models\Kardex\Kardex;
use Illuminate\Database\Eloquent\Model;


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
        return $this->belongsTo(Type::class);
    }

    public function details(){
        return $this->hasMany(Detail::class);
    }

}