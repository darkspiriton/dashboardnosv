<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;


class Questionnaire extends Model
{
    protected $table="questionnaires";

    public function questions(){
        return $this->belongsToMany(Question::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function answersP(){
        return $this->hasMany(AnswerProduct::class);
    }

    public function answersC(){
        return $this->hasMany(AnswerCustomer::class);
    }
}
