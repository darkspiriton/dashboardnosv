<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class DiscountLunch extends Model
{
    public function lunch(){
        return $this->belongsTo(Lunch::class);
    }
}
