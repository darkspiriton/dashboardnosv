<?php

namespace Dashboard\Models\Experimental;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $table="auxclients";
    protected $fillable = ["name","email","phone","dni","address","reference","facebook_id","facebook_name"];
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

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }

    public function setReferenceAttribute($value)
    {
        $this->attributes['reference'] = strtolower($value);
    }

    public function getEmailAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }

    public function getDniAttribute($value)
    {
        return is_null($value)?"No tiene":$value;
    }
}
