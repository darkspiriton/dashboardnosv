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

   	public function DetailStatusesCount()
   	{
   		return $this->hasOne(ProductDetailStatus::class, "status_id")->selectRaw("status_id, count(*) as count")->groupBy("status_id");
   	}

   	public function getDetailStatusesCountAttribute()
   	{
   		$detail = $this->relations["DetailStatusesCount"];
   		if ($detail) {
   			unset($this->attributes["product_status_id"]);
   			unset($detail->status_id);
   			return $detail->count;
   		} else {
   			return 0;
   		}
   	}
}
