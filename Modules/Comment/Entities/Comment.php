<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;

class Comment extends \BeyondCode\Comments\Comment implements Expandable, HasFilters
{
    use HasExpandScope;

    protected $fillable = [
        'title',
        'comment',
        'user_id',
        'is_approved',
//        'rate_quantity',
        'commentable_type',
        'commentable_id',
    ];

    protected $casts = [
        'is_approved'   => 'boolean',
//        'rate_quantity' => 'decimal:0',
    ];

    public function getExpandRelations(): array
    {
        return ['commentator', 'commentable', 'user'];
    }

    public function getSearchParams(): array
    {
        return ['title', 'comment'];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
