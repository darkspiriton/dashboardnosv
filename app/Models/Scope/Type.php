<?php

namespace Dashboard\Models\Scope;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected  $table='types_scope';
    
    public function scopes(){
        return $this->hasMany(Scope::class);
    }
}
