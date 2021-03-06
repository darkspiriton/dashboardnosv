<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Models\Experimental\State;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $table="auxclients";
    protected $fillable = ["name","email","phone","dni","address","reference","facebook_id","facebook_name","status_id"];
    public $timestamps = true;

    public function movements(){
        return $this->hasMany(Movement::class)->orderby('created_at','desc');        
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strlen($value)?strtolower($value):null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = strtolower($value);
    }

    public function setDniAttribute($value)
    {
        $this->attributes['dni'] = strlen($value)?strtolower($value):null;
    }

    public function getEmailAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }

    public function getDniAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }

    public function getAddressAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }

    public function getReferenceAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }

    public function state(){
        return $this->belognsTo(State::class);
    }
}
