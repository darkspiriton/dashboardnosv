<?php

namespace Dashboard;

use Illuminate\Database\Eloquent\Model;
use Dashboard\Role;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 'Birth_date', 'sex', 'photo', 'role_id', 'user', 'state',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function role(){
        return $this->hasOne(Role::class);
    }
}
