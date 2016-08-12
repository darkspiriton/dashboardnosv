<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table="auxclients";
    protected $id="id";

    public function movements(){
        return $this->hasMany(Movement::class)->orderby('created_at','desc');        
    }

}
