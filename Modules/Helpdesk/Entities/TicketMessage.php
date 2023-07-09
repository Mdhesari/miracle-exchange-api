<?php

namespace Modules\Helpdesk\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Helpdesk\Database\Factories\TicketMessageFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TicketMessage extends Model implements HasMedia, Expandable
{
    use SoftDeletes, HasFactory, InteractsWithMedia, HasExpandScope, HasUuids;

    const MEDIA_ATTACHMENTS = 'attachments';

    protected $fillable = [
        'message', 'is_read', 'user_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_ATTACHMENTS)->acceptsMimeTypes([
            'image/jpg', 'image/jpeg', 'image/png', 'application/pdf', 'text/plain', 'application/zip',
        ]);
    }

    public function getExpandRelations(): array
    {
        return [
            'user', 'ticket', 'media',
        ];
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addAttachment(UploadedFile $attachment): Media
    {
        return $this->addMedia($attachment)->toMediaCollection(self::MEDIA_ATTACHMENTS);
    }

    protected static function newFactory()
    {
        return app(TicketMessageFactory::class);
    }
}
