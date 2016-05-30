<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function questionnaires(){
        return $this->belongsToMany(Questionnaire::class,'questionnaires_questions','question_id');
    }

    public function options(){
        return $this->hasMany(Option::class);
    }
}
