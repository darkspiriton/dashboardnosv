<?php

namespace Dashboard\Models\Kardex;


use Illuminate\Database\Eloquent\Model;


class Attribute extends Model
{
    protected $table='attributes';

    protected $hidden = [
        'created_at','updated_at','pivot'
    ];

    public function kardexs()
    {
        return $this->belongsToMany(Kardex::class);
    }

    public function type(){
        return $this->belongsTo(Type_Attribute::class);
    }

}
