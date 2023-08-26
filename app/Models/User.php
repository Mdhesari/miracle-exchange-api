<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasFilters
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'national_code',
        'birthday',
        'gender',
        'email',
        'mobile',
        'banned_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime',
        'mobile_verified_at' => 'datetime',
        'birthday'           => 'date',
        'banned_at'          => 'datetime',
        'password'           => 'hashed',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setMobileAttribute($mobile)
    {
        $this->attributes['mobile'] = substr($mobile, -10);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }

    protected function getDefaultGuardName(): string
    {
        return 'api';
    }

    /**
     * @param mixed $user_id
     * @return bool
     */
    public function isOwner(mixed $user_id): bool
    {
        return intval($this->id) === intval($user_id);
    }

    public function getSubject(): mixed
    {
        return $this->full_name;
    }

    public function getSearchParams(): array
    {
        return ['first_name', 'last_name', 'email', 'mobile'];
    }
}
