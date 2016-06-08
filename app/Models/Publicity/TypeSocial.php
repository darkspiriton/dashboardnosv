<?php

namespace Dashboard\Models\Publicity;

use Dashboard\Models\Customer\Social;
use Illuminate\Database\Eloquent\Model;

class TypeSocial extends Model
{
    protected $table="types_socials";
    
    public function socials(){
        return $this->hasMany(Social::class);
    }
}
