<?php

namespace Dashboard\Models\Request;


use Dashboard\Models\Request\User;
use Dashboard\Models\Request\Photo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'requests_products';
    protected $id = 'id';  

    public function user(){
        return $this->belongsTo(User::class,'user_request_id');
    }

    public function userR(){
        return $this->belongsTo('Dashboard\User','user_id');
    }

    public function photos(){
        return $this->hasMany(Photo::class,'request_product_id');
    }
}
