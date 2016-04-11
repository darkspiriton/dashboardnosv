<?php

namespace Dashboard\Models\Product;

use Illuminate\Database\Eloquent\Model;


class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $table='types_attributes';

    public function attributes(){
        return $this->hasMany(Attribute::class);
    }



}