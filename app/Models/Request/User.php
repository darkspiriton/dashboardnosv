<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\Product;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Request\Application;

class User extends Model
{
    protected $table = 'users_requests';
    protected $id = 'id';

    public function requestAplications(){
        $this->hasMany(Application::class);
    }

    public function requestProducts(){
        $this->hasMany(Product::class);
    }

}
