<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class DiscountLunch extends Model
{
    protected $table="discounts_lunches";
    public function lunch(){
        return $this->belongsTo(Lunch::class);
    }
}
