<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    protected $table="alarms";

    public function products(){
        return $this->hasMany(Product::class);
    }
}
