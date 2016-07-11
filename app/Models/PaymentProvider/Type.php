<?php

namespace Dashboard\Models\PaymentProvider;

use Dashboard\Models\PaymentProvider\Payment;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = "types_discounts_providers";

    public function PaymentProvider(){
    	return $this->hasMany(Payment::class);
    }
}
