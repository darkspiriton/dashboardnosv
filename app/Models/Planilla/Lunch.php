<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Lunch extends Model
{

    public function employe(){
        return $this->belongsTo(Employe::class);
    }
    
    public function discount(){
        return $this->hasOne(DiscountLunch::class,'lunches_id', 'id');
    }
}
