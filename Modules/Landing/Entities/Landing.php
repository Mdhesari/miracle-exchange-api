<?php

namespace Modules\Landing\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Market\Entities\Market;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Landing extends Model
{
    use HasFactory, HasSlug;

    protected $table = 'landing';

    protected $fillable = [
        'title',
        'slug',
        'market_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function market(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
