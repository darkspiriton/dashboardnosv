<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    public function assist(){
        return $this->belongsTo(Assist::class);
    }
}
