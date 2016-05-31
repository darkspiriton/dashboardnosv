<?php

namespace Dashboard\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class AnswerProduct extends Model
{
    protected $table = "answers_products";
    protected $timestamp = false;
    protected $hidden = ["created_at","updated_at","product_id"];

    public function questionnaire(){
        return $this->belongsTo(Questionnaire::class);
    }

    public function option(){
        return $this->belongsTo(Option::class);
    }

    public function product(){
        return $this->belongsTo(AnswerProduct::class);
    }

}
