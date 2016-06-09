<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;


class Publicity extends Model
{
    protected $table='publicities';

    public function socials(){
        return $this->hasMany(Social::class);
    }

    public function processes(){
        return $this->hasMany(Process::class);
    }
}
