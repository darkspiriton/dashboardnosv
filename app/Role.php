<?php

namespace Dashboard;

use Illuminate\Database\Eloquent\Model;
use Dashboard\User;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'roles';

    protected $fillable = [
        'abrev', 'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
