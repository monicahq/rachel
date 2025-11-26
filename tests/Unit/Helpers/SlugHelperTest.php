<?php

declare(strict_types=1);

use App\Helpers\SlugHelper;

it('get slug for empty list', function (): void {
    $collection = collect();

    $slug = SlugHelper::generateUniqueSlug($collection, 'Test New Name', 'en');
    expect($slug)->toBe('test-new-name');
});

it('get unique slug', function (): void {
    $collection = collect([
        ['slug' => 'test-new-name'],
        ['slug' => 'test-new-name-1'],
        ['slug' => 'test-new-name-2'],
    ]);

    $slug = SlugHelper::generateUniqueSlug($collection, 'Test New Name', 'en');
    expect($slug)->toBe('test-new-name-3');
});
