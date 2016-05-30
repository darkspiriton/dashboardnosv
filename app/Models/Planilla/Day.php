<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    public function employees(){
        return $this->belongsToMany(Employee::class,'days_employees','employe_id');
    }
}
