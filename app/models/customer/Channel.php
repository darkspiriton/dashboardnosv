<?php

namespace Dashboard\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function socials(){
        return $this->hasMany(Social::class);
    }
}
