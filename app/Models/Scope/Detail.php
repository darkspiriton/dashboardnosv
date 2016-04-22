<?php

namespace Dashboard\Models\Scope;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected  $table='scope_details';

    public function scope(){
        return $this->belongsTo(Scope::class);
    }
}
