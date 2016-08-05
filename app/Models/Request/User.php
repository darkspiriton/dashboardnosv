<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\Product;
use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Request\Application;

class User extends Model
{

    protected $table = 'users_requests';
    protected $fillable = ["name","email","phone"];
    protected $hidden = ["updated_at"];

    public function requestAplications(){
       return $this->hasMany(Application::class);
    }

    public function requestProducts(){
        return $this->hasMany(Product::class);
    }

}
