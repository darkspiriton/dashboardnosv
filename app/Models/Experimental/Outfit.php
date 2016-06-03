<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    protected $table="outfits";

    public function products(){
        return $this->belongsToMany(Product::class,"products_outfits","outfit_id");
    }


}
