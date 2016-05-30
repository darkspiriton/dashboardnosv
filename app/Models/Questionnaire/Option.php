<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;


class Option extends Model
{
    protected $table="options";
    protected $hidden=[
      'created_at','updated_at'
    ];
    
    public function answersP(){
        return $this->hasMany(AnswerProduct::class);
    }

    public function answersC(){
        return $this->hasMany(AnswerCustomer::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
