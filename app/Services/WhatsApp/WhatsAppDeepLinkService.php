<?php

namespace App\Services\WhatsApp;

use App\Models\Guest;
use App\Models\Wedding;
use App\Services\Contracts\WhatsAppServiceInterface;

class WhatsAppDeepLinkService implements WhatsAppServiceInterface
{
    /**
     * Build the personalized invitation message content for a guest.
     */
    public function buildInvitationMessage(Guest $guest, Wedding $wedding): string
    {
        $wedding->loadMissing('couple');
        $couple = $wedding->couple;

        $groom = $couple->groom_name ?? 'Mempelai Pria';
        $bride = $couple->bride_name ?? 'Mempelai Wanita';
        
        $invitationUrl = route('invitation.guest', [
            'slug' => $wedding->slug,
            'guestSlug' => $guest->slug,
        ]);

        return "Kepada Yth.\n"
            . "Bapak/Ibu/Saudara/i : {$guest->name}\n\n"
            . "Tanpa mengurangi rasa hormat, perkenankan kami mengundang Anda untuk hadir dalam momen bahagia pernikahan kami:\n\n"
            . "💍 {$groom} & {$bride} 💍\n\n"
            . "Untuk melihat detail acara, waktu, lokasi, dan memberikan konfirmasi kehadiran, silakan buka tautan undangan digital berikut:\n"
            . "{$invitationUrl}\n\n"
            . "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir serta memberikan doa restu.\n\n"
            . "Terima kasih.";
    }

    /**
     * Generate the WhatsApp wa.me deep link.
     */
    public function generateShareUrl(Guest $guest, Wedding $wedding): string
    {
        $message = $this->buildInvitationMessage($guest, $wedding);
        $encodedText = rawurlencode($message);

        $phone = WhatsAppNormalizer::normalize($guest->whatsapp_number);

        if ($phone) {
            return "https://wa.me/{$phone}?text={$encodedText}";
        }

        // Generic WhatsApp share URL if guest phone is empty
        return "https://wa.me/?text={$encodedText}";
    }
}
