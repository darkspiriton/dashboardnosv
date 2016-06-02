<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table="aux2customers";
    protected $hidden = ['created_at','updated_at'];
    public function answers(){
       return $this->hasMany(AnswerCustomer::class);
    }

}
