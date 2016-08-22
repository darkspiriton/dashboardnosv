<?php

namespace Dashboard\Models\notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	public function type(){
		return $this->BelongsTo(Notification_type::class);
	}
}
