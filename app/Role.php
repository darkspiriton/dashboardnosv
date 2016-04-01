<?php

namespace Dashboard;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo('App\User','foreign_key','id');
    }
}
