<?php

namespace Dashboard\Models\PaymentProvider;

use Illuminate\Database\Eloquent\Model;

class TypePayment extends Model
{
    protected $table='types_payments_providers';
    protected $id='id';
    
    public function payment(){
        return $this->hasMany(Payment::class);
    }
}
