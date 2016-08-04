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
        $this->belongsTo(User::class);
    }

    public function userR(){
        $this->belongsTo('Dashboard\User');
    }

    public function photos(){
        $this->hasMany(Photo::class);
    }
}
