<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="aux2products";

    protected $hidden = ['created_at','updated_at'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function answers(){
        return $this->hasMany(AnswerProduct::class);
    }
}
