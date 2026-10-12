<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'opening_text',
        'closing_text',
        'music_url',
        'background_image',
        'primary_color',
        'secondary_color',
        'font_family',
        'show_gift',
        'show_rsvp',
        'show_guestbook',
    ];

    protected function casts(): array
    {
        return [
            'show_gift' => 'boolean',
            'show_rsvp' => 'boolean',
            'show_guestbook' => 'boolean',
        ];
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
