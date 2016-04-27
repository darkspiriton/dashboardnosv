<?php

namespace Dashboard\Models\Sale;

use Dashboard\User;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sale(){
        return $this->belongsTo(Sale::class);
    }

}
