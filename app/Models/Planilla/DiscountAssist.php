<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class DiscountAssist extends Model
{
    protected $table="discounts_assists";
    public function assist(){
        return $this->belongsTo(Assist::class);
    }


}
