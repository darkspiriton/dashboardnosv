<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class AnswerCustomer extends Model
{
    protected $table="answers_customers";

    protected $hidden = ['created_at','updated_at','id','customer_id','option_id','questionnaire_id'];


    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function option(){
        return $this->belongsTo(Option::class);
    }

    public function questionnaire(){
        return $this->belongsTo(Questionnaire::class);
    }

}
