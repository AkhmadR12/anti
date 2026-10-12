<?php

namespace App\Services\Contracts;

use App\Models\Guest;
use App\Models\Wedding;

interface WhatsAppServiceInterface
{
    /**
     * Build the personalized invitation message content for a guest.
     */
    public function buildInvitationMessage(Guest $guest, Wedding $wedding): string;

    /**
     * Generate the share URL (e.g., wa.me deep link or API dispatch URI).
     */
    public function generateShareUrl(Guest $guest, Wedding $wedding): string;
}
