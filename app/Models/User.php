<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Lang;
use Laravel\Sanctum\HasApiTokens;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Notification\Traits\Notifiable;
use Modules\Wallet\Traits\HasTransaction;
use Modules\Wallet\Traits\HasWallet;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasFilters, HasMedia, Expandable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles, HasPermissions, InteractsWithMedia, HasExpandScope, HasWallet, HasTransaction;

    const MEDIA_NATIONAL_ID = 'national_id';
    const MEDIA_NATIONAL_ID_BACK = 'national_id_back';
    const MEDIA_FACE_SCAN = 'face_scan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'invitation_code',
        'inviter_id',
        'inviter_has_revenue',
        'national_code',
        'birthday',
        'status',
        'gender',
        'email',
        'mobile',
        'banned_at',
        'password',
        'meta',
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
        'wallet_quantity',
        'status_trans',
        'available_status',
        'available_gender',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'   => 'datetime',
        'mobile_verified_at'  => 'datetime',
        'birthday'            => 'date',
        'banned_at'           => 'datetime',
        'password'            => 'hashed',
        'meta'                => 'array',
        'inviter_has_revenue' => 'boolean',
    ];

    public function inviter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function getStatusTransAttribute()
    {
        return Lang::has($key = 'status.general.'.$this->status) ? Lang::get($key) : $this->status;
    }

    public function getFullNameAttribute()
    {
        return is_null($this->first_name) && is_null($this->last_name) ? __('user.user') : trim("{$this->first_name} {$this->last_name}");
    }

    public function getLevelAttribute()
    {
        return $this->asDecimal($this->status === UserStatus::Accepted->name ? 2 : 1, 0);
    }

    public function getAvailableStatusAttribute()
    {
        return array_column(UserStatus::cases(), 'name');
    }

    public function getAvailableGenderAttribute()
    {
        return array_column(UserGender::cases(), 'name');
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

    /**
     * @param string|UploadedFile $file
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addNationalIdImage(string|UploadedFile $file): mixed
    {
        return $this->addMedia($file)->toMediaCollection(self::MEDIA_NATIONAL_ID);
    }

    /**
     * @param string|UploadedFile $file
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addNationalIdImageBack(string|UploadedFile $file): mixed
    {
        return $this->addMedia($file)->toMediaCollection(self::MEDIA_NATIONAL_ID_BACK);
    }

    /**
     * @param string|UploadedFile $file
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addFaceScanImage(string|UploadedFile $file): mixed
    {
        return $this->addMedia($file)->toMediaCollection(self::MEDIA_FACE_SCAN);
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_FACE_SCAN)->acceptsMimeTypes([
            'image/jpg', 'image/jpeg', 'image/png',
        ])->singleFile();

        $this->addMediaCollection(self::MEDIA_NATIONAL_ID)->acceptsMimeTypes([
            'image/jpg', 'image/jpeg', 'image/png',
        ])->singleFile();

        $this->addMediaCollection(self::MEDIA_NATIONAL_ID_BACK)->acceptsMimeTypes([
            'image/jpg', 'image/jpeg', 'image/png',
        ])->singleFile();
    }

    public function getExpandRelations(): array
    {
        return ['media'];
    }

    public function isAccepted(): bool
    {
        return $this->status === UserStatus::Accepted->name;
    }

    public function updateInviter(int $user_id): bool
    {
        return $this->forceFill([
            'inviter_id' => $user_id,
        ])->save();
    }

    public function getOwner()
    {
        return $this->full_name;
    }

    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), 'model', 'model_type', 'model_id', 'uuid');
    }
}
