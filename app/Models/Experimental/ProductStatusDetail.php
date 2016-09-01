<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class ProductStatusDetail extends Model
{
	protected $hidden = ["created_at", "updated_at"];

    public function product_status()
    {
    	return $this->belongsTo(ProductStatus::class);
    }

    public function product_detail_statuses()
    {
    	return $this->hasMany(ProductDetailStatus::class, "status_id");
    }
}
