<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function questionnaires(){
        return $this->hasMany(Questionnaire::class);
    }
}
