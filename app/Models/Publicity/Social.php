<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $table='auxsocials';

    public function publicity(){
        return $this->belongsTo(Publicity::class);
    }
    
    public function type(){
        return $this->belongsTo(TypeSocial::class);
    }

}
