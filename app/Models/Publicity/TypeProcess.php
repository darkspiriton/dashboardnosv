<?php

namespace Dashboard\Models\Publicity;

use Illuminate\Database\Eloquent\Model;

class TypeProcess extends Model
{
    protected $table="types_processes";
    
    public function process(){
        $this->hasMany(Process::class,'type_process_id');
    }
}
