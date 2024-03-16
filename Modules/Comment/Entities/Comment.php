<?php

namespace Modules\Comment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Comment\Database\Factories\CommentFactory;

class Comment extends \BeyondCode\Comments\Comment implements Expandable, HasFilters
{
    use HasExpandScope, HasFactory;

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
        'is_approved' => 'boolean',
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

    protected static function newFactory()
    {
        return app(CommentFactory::class);
    }
}
