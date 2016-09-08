<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class ProductDetailStatus extends Model
{
	protected $hidden = ["created_at", "updated_at"];

    public function Product_status_detail()
    {
    	return $this->belongsTo(ProductStatusDetail::class, "status_id");
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
