<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{

    protected $table='types_movements';
    public function movements(){
        return $this->hasMany(Movements::class);
    }
}
