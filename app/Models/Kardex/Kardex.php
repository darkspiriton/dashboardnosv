<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table='kardexs';

    public function movements(){
        return $this->hasMany(Movements::class);
    }
}

