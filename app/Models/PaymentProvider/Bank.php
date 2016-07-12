<?php

namespace Dashboard\Models\PaymentProvider;


use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table='banks';
    protected $id='id';

    public function payments(){
        return $this->hasMany(Payment::class);
    }    
    
}
