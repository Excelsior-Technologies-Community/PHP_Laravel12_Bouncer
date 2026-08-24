<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'database_notifications',
        'realtime_notifications',
    ];

    protected $casts = [
        'email_notifications' => 'array',
        'database_notifications' => 'array',
        'realtime_notifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
