<?php

namespace App\Services\WhatsApp;

class WhatsAppNormalizer
{
    /**
     * Normalize a phone number to standard international format without '+' (e.g., 628123456789).
     */
    public static function normalize(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        // Remove non-digit characters except leading plus
        $phone = preg_replace('/[^\d+]/', '', trim($phone));

        // If starts with +, strip it
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }

        // If starts with 08, replace with 628
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Check if a phone number is a valid normalized Indonesian WhatsApp number.
     */
    public static function isValid(string $phone): bool
    {
        $normalized = self::normalize($phone);
        return (bool) preg_match('/^628[0-9]{8,12}$/', $normalized);
    }
}
