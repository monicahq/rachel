<?php

declare(strict_types=1);

namespace App\Helpers;

final class SlugHelper
{
    public static function generateUniqueSlug($collection, string $name, string $locale): string
    {
        $slug = \Illuminate\Support\Str::slug($name, language: $locale);

        $collection->where('slug', 'like', "%$slug");
        $i = 0;
        while ($collection->where('slug', $slug)->isNotEmpty()) {
            $i++;
            $slug = \Illuminate\Support\Str::slug("{$name} {$i}", language: $locale);
        }

        return $slug;
    }
}
