<?php

namespace Dashboard\Dashboard\Models\Kardex;

use Dashboard\Models\Kardex\Attribute;
use Dashboard\Models\Kardex\GroupAttribute;
use Illuminate\Database\Eloquent\Model;

class AttributesKardex extends Model
{
    protected $table = 'attributes_kardexs';
    public function attribute(){
        return $this->belongsTo(Attribute::class);
    }
    public function group_attribute(){
        return $this->belongsTo(GroupAttribute::class);
    }
}
