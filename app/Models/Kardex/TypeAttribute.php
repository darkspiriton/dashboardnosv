<?php

namespace Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;


class TypeAttribute extends Model
{
    protected $table='types_attributes';
    
    public function att(){
        return $this->hasMany(Attribute::class,'type_id', 'id');
    }
}
