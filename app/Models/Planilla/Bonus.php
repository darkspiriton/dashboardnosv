<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    public function employe(){
        return $this->belongsTo(Employe::class);
    }
}
