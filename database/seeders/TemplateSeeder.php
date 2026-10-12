<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Elegant Romance',
                'slug' => 'elegant',
                'thumbnail' => '/images/templates/elegant-thumb.jpg',
                'description' => 'Desain anggun dengan ornamen klasik, tipografi serif mewah, dan palet warna rose gold.',
                'view_path' => 'elegant',
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Modern Floral',
                'slug' => 'floral',
                'thumbnail' => '/images/templates/floral-thumb.jpg',
                'description' => 'Sentuhan dedaunan tropis dan bunga lembut dengan aksen pastel yang segar dan romantis.',
                'view_path' => 'floral',
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Minimalist Sage',
                'slug' => 'minimalist',
                'thumbnail' => '/images/templates/minimalist-thumb.jpg',
                'description' => 'Gaya bersih, modern, dan bernuansa earthy sage green untuk pernikahan kontemporer.',
                'view_path' => 'minimalist',
                'is_active' => true,
                'is_premium' => false,
            ],
            [
                'name' => 'Luxury Gold',
                'slug' => 'luxury',
                'thumbnail' => '/images/templates/luxury-thumb.jpg',
                'description' => 'Kemewahan nuansa emas dan monokrom hitam/navy untuk pesta pernikahan megah.',
                'view_path' => 'luxury',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'name' => 'Classic Botanical',
                'slug' => 'classic',
                'thumbnail' => '/images/templates/classic-thumb.jpg',
                'description' => 'Estetika klasik dengan border vintage dan ilustrasi botanis abadi.',
                'view_path' => 'classic',
                'is_active' => true,
                'is_premium' => false,
            ],
        ];

        foreach ($templates as $tmpl) {
            Template::updateOrCreate(['slug' => $tmpl['slug']], $tmpl);
        }
    }
}
