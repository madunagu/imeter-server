<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    public $fillable = [
        'notification_type','title','body','is_seen','meter_id'
    ];
}
