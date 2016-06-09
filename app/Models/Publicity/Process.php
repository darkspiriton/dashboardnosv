<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table='processes';

    public function publicity(){
        return $this->belongsTo(Publicity::class);
    }
    
    public function type(){
        return $this->belongsTo(TypeProcess::class);
    }    
}
