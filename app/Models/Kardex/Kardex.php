<?php

namespace Dashboard\Models\Kardex;

use Dashboard\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use DB;

class Kardex extends Model
{
    protected $hidden = [
        'created_at','updated_at'
    ];

    protected $table = 'kardexs';

    public function movements(){
        return $this->hasMany(Movements::class);
    }

    public function group_attribute(){
        return $this->belongsTo(GroupAttribute::class)->select('id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function getKardexStock($id){
        return DB::table('kardexs as k')
                    ->select(array('k.id', 'p.name', 'p.price',DB::raw('GROUP_CONCAT(a.valor order by a.valor asc SEPARATOR " ") as attrs')))
                    ->join('products as p', function ($join) {
                        $join->on('k.product_id', '=', 'p.id');
                    })
                    ->join('groups_attributes as ga', function ($join) {
                        $join->on('k.group_attribute_id', '=', 'ga.id');
                    })
                    ->join('attributes_kardexs as ak', function ($join) {
                        $join->on('ga.id', '=', 'ak.group_attribute_id');
                    })
                    ->join('attributes as a', function ($join) {
                        $join->on('ak.attribute_id', '=', 'a.id');
                    })
                    ->where('k.product_id',$id)
                    ->where('k.stock',1)
                    ->groupBy('k.id')
                    ->get();
    }
}

