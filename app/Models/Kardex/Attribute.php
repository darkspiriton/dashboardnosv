<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Dashboard\Models\Kardex\AttributesKardex;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table='attributes';

    protected $hidden = [
        'created_at','updated_at','pivot'
    ];

    public function attributes_kardexs()
    {
        return $this->hasMany(AttributesKardex::class);
    }
    
    public function type(){
        return $this->belongsTo(Type_Attribute::class);
    }

}
