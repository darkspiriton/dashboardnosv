<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $table='auxsocials';

    public $timestamps = false;

    public function publicity(){
        return $this->belongsTo(Publicity::class);
    }
    
    public function type(){
        return $this->belongsTo(TypeSocial::class, 'type_social_id');
    }

}
