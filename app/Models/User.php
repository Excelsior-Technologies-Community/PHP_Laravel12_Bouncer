<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use App\Models\Tenant;
use App\Models\Media;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\NotificationPreference;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRolesAndAbilities;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'avatar',
        'tenant_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function avatarMedia()
    {
        return $this->media()->where('collection_name', 'avatar');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notificationPreference()
    {
        return $this->hasOne(NotificationPreference::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $avatarMedia = $this->avatarMedia()->first();
        if ($avatarMedia) {
            return asset('storage/' . $avatarMedia->file_name);
        }

        return asset('images/default-avatar.png');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
