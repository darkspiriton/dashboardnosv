<?php

namespace Dashboard\Models\Prestashop;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Prestashop\Request;

class User extends Model
{
    protected $table='users_prestashop';
    protected $id='id';

    public function requests(){
        return $this->hasMany(Request::class);
    }  
    
}
