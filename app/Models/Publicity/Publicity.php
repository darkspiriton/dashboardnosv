<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Process;

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
