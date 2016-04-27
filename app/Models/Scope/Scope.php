<?php

namespace Dashboard\Models\Scope;

use Dashboard\Models\Customer\Channel;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    protected  $table = 'scopes';
    public $timestamps = false;

    public function type(){
        return $this->belongsTo(Type::class);
    }
    
    public function details(){
        return $this->hasMany(Detail::class);
    }

    public function channel(){
        return $this->belongsTo(Channel::class);
    }
}
