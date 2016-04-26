<?php

namespace Dashboard\Models\Kardex;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{

    protected $hidden = [
        'created_at','updated_at'
    ];

    protected $table = 'kardexs';

    public function movements(){
        return $this->hasMany(Movements::class);
    }

    public function group_kardex(){
        return $this->belongsTo(GroupAttribute::class);
    }


}

