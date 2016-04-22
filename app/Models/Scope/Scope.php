<?php

namespace Dashboard\Models\Scope;

use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    protected  $table='scopes';

    public function type(){
        return $this->belongsTo(Type::class);
    }
    
    public function details(){
        return $this->hasMany(Detail::class);
    }
}
