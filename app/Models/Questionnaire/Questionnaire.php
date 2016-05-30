<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    public function questions(){
        return $this->hasMany(Question::class,'questionnaires_questions','question_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
