<?php

namespace Dashboard\Models\PaymentProvider;

use Dashboard\Models\Experimental\Provider;
use Dashboard\Models\PaymentProvider\Type;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table="payments_providers";
    
    public function provider(){
        return $this->hasOne(Provider::class);
    }
    
    public function details(){
        return $this->hasMany(Detail::class);
    }   
    
    public function type_discount(){
    	return $this->belongsTo(Type::class);
    }
}
