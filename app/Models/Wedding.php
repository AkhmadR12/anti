<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wedding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'slug',
        'title',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function couple(): HasOne
    {
        return $this->hasOne(Couple::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(WeddingEvent::class)->orderBy('event_date')->orderBy('start_time');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('sort_order');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(WeddingSetting::class);
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class)->orderBy('name');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class)->latest();
    }

    public function loveStories(): HasMany
    {
        return $this->hasMany(LoveStory::class)->orderBy('sort_order');
    }

    public function gifts(): HasMany
    {
        return $this->hasMany(WeddingGift::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
