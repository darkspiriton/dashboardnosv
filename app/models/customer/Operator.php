<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    public function phones(){
        return $this->hasMany(Phone::class);
    }
}
