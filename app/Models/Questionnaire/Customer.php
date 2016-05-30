<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table="aux2customers";

    public function answers(){
       return $this->hasMany(AnswerCustomer::class);
    }

}
