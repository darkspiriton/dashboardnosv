<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Type_Attribute extends Model
{
    protected $table='types_attributes';
    
    public function attributes(){
        $this->hasMany(Attribute::class);        
    }
}
