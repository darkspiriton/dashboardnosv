<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Assist extends Model
{
    protected $table = 'assists';
    public function discount(){
        return $this->hasOne(DiscountAssist::class);
    }

    public function extra(){
        return $this->hasOne(Extra::class);
    }

    public function employe(){
        return $this->belongsTo(Employee::class);
    }   
}
