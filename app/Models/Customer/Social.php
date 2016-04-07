<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function channel(){
        return $this->belongsTo(Channel::class);
    }
}
