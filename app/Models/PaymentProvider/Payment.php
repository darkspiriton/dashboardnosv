<?php

namespace Dashboard\Models\PaymentProvider;

use Dashboard\Models\Experimental\Provider;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table='payments_providers';
    protected $id='id';
    public $timestamps=false;
    
    public function provider(){
        return $this->hasOne(Provider::class);
    }
    
    public function details(){
        return $this->hasMany(Detail::class);
    }   
    
    public function bank(){
        return $this->belongsTo(Bank::class);
    }
    
    public function typeD(){
        return $this->belongsTo(TypeDiscount::class,"type_discount_id");
    }
    
    public function typeP(){
        return $this->belongsTo(TypePayment::class,"type_payment_id");
    }
    
}
