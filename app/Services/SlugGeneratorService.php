<?php

namespace App\Services;

use App\Models\Wedding;
use Illuminate\Support\Str;

class SlugGeneratorService
{
    /**
     * Generate a unique slug for a wedding.
     */
    public function generateForWedding(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if (blank($baseSlug)) {
            $baseSlug = 'undangan-pernikahan';
        }

        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        return $slug;
    }

    protected function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        $query = Wedding::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
