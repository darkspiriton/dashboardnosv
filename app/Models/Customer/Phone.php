<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $table = 'Phones';

    public $timestamps = false;

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function operator(){
        return $this->belongsTo(Operator::class);
    }
}