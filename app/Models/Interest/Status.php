<?php

namespace Dashboard\Models\Interest;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function interests(){
        return $this->hasMany(Interest::class);
    }
}
