<?php

namespace Dashboard\Models\PaymentProvider;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table="payments_details";
    public $timestamps=false;

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
}
