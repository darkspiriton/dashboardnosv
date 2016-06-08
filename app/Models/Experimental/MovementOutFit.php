<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class MovementOutFit extends Model
{
    public $table = 'aux_outfit_movements';

    protected $hidden = ['created_at','updated_at'];

    public function products(){
        return $this->belongsToMany(Product::class,'aux_outfit_movements_detail','outfit_movement_id');
    }

    public function outfit(){
        return $this->belongsTo(Outfit::class);
    }
}
