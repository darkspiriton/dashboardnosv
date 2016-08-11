<?php

namespace Dashboard\Models\Prestashop;

use Dashboard\Models\Prestashop\User;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Prestashop\Product;

class Request extends Model
{
    protected $table='requests_prestashop';
    protected $id='id';
    protected $appends = ['cantProduct'];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCantProductAttribute(){
        $products= Product::where('request_id',$this->attributes['id'])->get();
        return $products->count();
    }
}
