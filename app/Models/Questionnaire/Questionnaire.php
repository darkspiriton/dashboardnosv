<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $hidden = ['created_at','updated_at','pivot'];

    public function questions(){
        return $this->belongsToMany(Question::class,'questionnaires_questions','questionnaire_id');
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
