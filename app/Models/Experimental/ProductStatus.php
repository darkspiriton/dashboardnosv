<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    public function status_details()
    {
    	return $this->hasMany(ProductStatusDetail::class);
    }
}
