<?php

namespace Dashboard\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class Notification_type extends Model
{
    public function notifications(){
    	return $this->HasMany(Notification::class);
    }
}
