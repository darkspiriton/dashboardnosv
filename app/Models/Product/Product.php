<?php

namespace Dashboard\Models\Product;

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
        'password',
    ];
    
    protected  $table='products';

    public function kardexs(){
        return $this->hasMany(Kardex::class);
    }

}