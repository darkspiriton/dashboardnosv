<?php

namespace Dashboard\Models\notification;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = ["title", "body", "event", "type_id", "status"];

	public function type(){
		return $this->BelongsTo(Notification_type::class);
	}
}
