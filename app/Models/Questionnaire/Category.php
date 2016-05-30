<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table="categories";

    public function questionnaires(){
        return $this->hasMany(Questionnaire::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
}
