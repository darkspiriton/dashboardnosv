<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class MovementOutFit extends Model
{
    public $table = 'aux_outfit_movements';

    public function products(){
        return $this->belongsToMany(Product::class,'aux_outfit_movements_detail','outfit_movement_id');
    }
}
