<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\User;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'requests_applications';
    protected $id = 'id';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
