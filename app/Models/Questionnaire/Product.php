<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table="aux2products";

    public function categories(){
        return $this->belongsTo(Category::class);
    }

    public function answers(){
        return $this->hasMany(AnswerProduct::class);
    }
}
