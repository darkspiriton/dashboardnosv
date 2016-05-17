<?php

namespace Dashboard\Models\Planilla;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table="employees";

    protected $hidden = ['created_at','updated_at'];


    public function days(){
        return $this->belongsToMany(Day::class,'days_employees','employe_id')->withPivot('start_time', 'end_time')->withTimestamps();
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function lunches(){
        return $this->hasMany(Lunch::class);
    }

    public function bonuses(){
        return $this->hasMany(Bonus::class);
    }

    public function salaries(){
        return $this->hasMany(Salary::class);
    }

    public function assists(){
        return $this->hasMany(Assist::class);
    }


}
