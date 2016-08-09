<?php

namespace Dashboard\Models\Request;

use Dashboard\Models\Request\Product;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos_products';
    protected $id = 'id';
    protected $appends = ['link'];
    protected $hidden = ['url'];
    public $timestamps = false;

    public function product(){
        return $this->belongsTo(Product::class, "request_product_id");
    }

    public function getLinkAttribute(){
        return url('/img/request/'.$this->attributes['url']);
    }

}
