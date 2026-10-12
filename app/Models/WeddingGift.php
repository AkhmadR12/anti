<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingGift extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id',
        'bank_or_wallet_name',
        'account_number',
        'account_holder_name',
        'qr_code_path',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }
}
