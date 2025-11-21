<?php

declare(strict_types=1);

use App\Helpers\CollectionHelper;
use Illuminate\Support\Facades\App;

it('sorts by collator base', function (): void {
    $collection = collect([
        ['name' => 'a'],
        ['name' => 'c'],
        ['name' => 'b'],
    ]);
    $collection = CollectionHelper::sortByCollator($collection, 'name');

    expect(array_values($collection->toArray()))->toBe([
        ['name' => 'a'],
        ['name' => 'b'],
        ['name' => 'c'],
    ]);
});

it('sorts by collator macro', function (): void {
    $collection = collect([
        ['name' => 'a'],
        ['name' => 'c'],
        ['name' => 'b'],
    ]);
    $collection = $collection->sortByCollator('name');

    expect(array_values($collection->toArray()))->toBe([
        ['name' => 'a'],
        ['name' => 'b'],
        ['name' => 'c'],
    ]);
});

it('sorts by collator callback', function (): void {
    $collection = collect([
        ['name' => 'a'],
        ['name' => 'c'],
        ['name' => 'b'],
    ]);
    $collection = $collection->sortByCollator(fn(array $item) => $item['name']);

    expect(array_values($collection->toArray()))->toBe([
        ['name' => 'a'],
        ['name' => 'b'],
        ['name' => 'c'],
    ]);
});

it('sorts by collator default collation', function (): void {
    App::setLocale('en');

    $collection = collect([
        ['name' => 'cote'],
        ['name' => 'côté'],
        ['name' => 'coté'],
        ['name' => 'côte'],
    ]);
    $collection = CollectionHelper::sortByCollator($collection, 'name');

    expect(array_values($collection->toArray()))->toBe([
        ['name' => 'cote'],
        ['name' => 'coté'],
        ['name' => 'côte'],
        ['name' => 'côté'],
    ]);
});

it('sorts by collator french collation', function (): void {
    App::setLocale('fr_FR');

    $collection = collect([
        ['name' => 'cote'],
        ['name' => 'côté'],
        ['name' => 'coté'],
        ['name' => 'côte'],
    ]);
    $collection = CollectionHelper::sortByCollator($collection, 'name');

    expect(array_values($collection->toArray()))->toBe([
        ['name' => 'cote'],
        ['name' => 'côte'],
        ['name' => 'coté'],
        ['name' => 'côté'],
    ]);
});

it('gets collator french collation', function (): void {
    $collator = CollectionHelper::getCollator('fr');

    expect($collator->getAttribute(Collator::FRENCH_COLLATION))->toBe(Collator::ON);
    expect($collator->getLocale(Locale::VALID_LOCALE))->toBe('fr');
});
