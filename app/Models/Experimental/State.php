<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Experimental\Client;

class State extends Model
{
    public function clients(){
        return $this->hasMany(Client::class);
    }
}
