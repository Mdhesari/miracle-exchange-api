<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP extends Model
{

    protected $table = 'otps';

    /**
     * @var string[]
     */

    protected $fillable = ['mobile', 'otp', 'otp_sent_at', 'otp_is_verified'];

    /**
     * @var string[]
     */

    protected $casts = [
        'otp_sent_at'     => 'datetime',
        'otp_is_verified' => 'boolean'
    ];

    /**
     * @param $query
     * @return mixed
     */

    public function scopeVerified($query): mixed
    {
        return $query->where('otp_is_verified', true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeUnverified($query): mixed
    {
        return $query->where('otp_is_verified', false);
    }
}
