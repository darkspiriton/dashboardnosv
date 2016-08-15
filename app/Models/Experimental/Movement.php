<?php

namespace Dashboard\Models\Experimental;

use Dashboard\User;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $table = "auxmovements";
    protected $id = "id";
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function user(){
    	return $this->belongsTo(User::class)->select("id","first_name");
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
}