<?php

namespace Dashboard\Models\Prestashop;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Prestashop\Request;

class Product extends Model
{
    protected $table='products_prestashop';
    protected $id='id';
    protected $appends = ['priceSubTotal'];
    public $timestamps = false;

    public function request(){
        return $this->belognsTo(Request::class);
    }

    public function getPriceSubTotalAttribute(){
        $cant=(int)$this->attributes['cant'];
        $price=(float)$this->attributes['price'];        
        $subTotalPrice=$cant*$price;
        return 'S/.'.$subTotalPrice;
    }
}
