<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Phones;

class Operator extends Model
{
    public function phones(){
        return $this->hasMany(Phones::class);
    }
}
