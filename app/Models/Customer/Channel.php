<?php

namespace Dashboard\Models\Customer;

use Dashboard\Models\Scope\Scope;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';

    public $timestamps = false;

    public function socials(){
        return $this->hasMany(Social::class);
    }

    public function scopes(){
        return $this->hasMany(Scope::class);
    }
}
