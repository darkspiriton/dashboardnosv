<?php

namespace Dashboard\models\customer;

use Illuminate\Database\Eloquent\Model;
use Dashboard\models\customer\Socials;

class Channel extends Model
{
    public function socials(){
        return $this->hasMany(Socials::class);
    }
}
