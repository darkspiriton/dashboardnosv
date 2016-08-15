<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table="auxclients";
    protected $fillable = ["name","email","phone","dni","address","reference"];
    public $timestamps = false;

    public function movements(){
        return $this->hasMany(Movement::class)->orderby('created_at','desc');        
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = strtolower($value);
    }

    public function setDniNameAttribute($value)
    {
        $this->attributes['dni'] = strtolower($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }

    public function setReferenceAttribute($value)
    {
        $this->attributes['reference'] = strtolower($value);
    }

}
