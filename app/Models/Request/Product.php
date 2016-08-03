<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\User;
use Dashboard\Models\Request\Photo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'requests_products';

    public function user(){
        $this->belongsTo(User::class);
    }

    public function photos(){
        $this->hasMany(Photo::class);
    }
}
