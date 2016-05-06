<?php

namespace Dashboard\Models\Order;

use Illuminate\Database\Eloquent\Model;

class StatusCall extends Model
{
    protected $table='status_call';
    public function calls(){
        return $this->hasMany(Call::class);
    }
}
