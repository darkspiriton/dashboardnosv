<?php

namespace Dashboard\Models\PaymentProvider;

use Illuminate\Database\Eloquent\Model;

class TypeDiscount extends Model
{
    protected $table='types_discounts_providers';
    protected $id='id';

    public function payment(){
        return $this->hasMany(Payment::class);
    }
}
