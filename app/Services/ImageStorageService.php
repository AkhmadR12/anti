<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageStorageService
{
    /**
     * Store an uploaded image in a scoped directory under public disk.
     */
    public function storeImage(UploadedFile $file, int $weddingId, string $folder = 'gallery'): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid() . '.' . strtolower($extension);
        $path = "weddings/{$weddingId}/{$folder}";

        // Save to public storage
        return $file->storeAs($path, $filename, 'public');
    }

    /**
     * Delete an image from storage securely, ensuring it resides in the wedding folder.
     */
    public function deleteImage(?string $path, int $weddingId): bool
    {
        if (blank($path)) {
            return false;
        }

        // Safety check: ensure file path belongs to this wedding's directory
        $expectedPrefix = "weddings/{$weddingId}/";
        if (!str_starts_with($path, $expectedPrefix)) {
            return false;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
