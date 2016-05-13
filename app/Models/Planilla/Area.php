<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function employees(){
        return $this->hasMany(Employe::class);
    }
}
