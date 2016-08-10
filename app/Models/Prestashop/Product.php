<?php

namespace Dashboard\Models\Prestashop;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Prestashop\Request;

class Product extends Model
{
    protected $table='products_prestashop';
    protected $id='id';

    public function request(){
        return $this->belognsTo(Request::class);
    }
}
