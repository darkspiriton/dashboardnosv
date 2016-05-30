<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table="questions";
    
    public function questionnaires(){
        return $this->belongsToMany(Questionnaire::class);        
    }

    public function options(){
        return $this->hasMany(Option::class);
    }
}
