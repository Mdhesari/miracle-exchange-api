<?php

namespace Modules\Market\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'price', 'market_id', 'date', 'name', 'persian_name', 'symbol_char', 'symbol',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public $timestamps = false;
}
