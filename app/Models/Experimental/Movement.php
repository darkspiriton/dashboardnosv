<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $table = "auxmovements";
    
    public function product(){
        return $this->belongsTo(Movement::class);
    }
}
