<?php

namespace Dashboard\Models\Product;

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

    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

}