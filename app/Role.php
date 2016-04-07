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
        'abrev', 'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
