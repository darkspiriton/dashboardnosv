<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $hidden = ['created_at','updated_at'];

    public function questions(){
        return $this->belongsToMany(Question::class,'questionnaires_questions','questionnaire_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
