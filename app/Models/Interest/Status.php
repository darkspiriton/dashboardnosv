<?php

namespace Dashboard\Models\Interest;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table = 'status_interests';

    public function interests(){
        return $this->hasMany(Interest::class);
    }
}
