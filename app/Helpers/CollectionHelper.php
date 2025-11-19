<?php

declare(strict_types=1);

namespace App\Helpers;

use Collator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

final class CollectionHelper
{
    /**
     * Sort the collection using the given callback.
     */
    public static function sortByCollator(Collection $collect, callable|string $callback, int $options = Collator::SORT_STRING, bool $descending = false): Collection
    {
        $results = [];

        $callback = self::valueRetriever($callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($collect->all() as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        // Using Collator to sort the array, with locale-sensitive sort ordering support.
        self::getCollator()->asort($results, $options);
        if ($descending) {
            $results = array_reverse($results);
        }

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $collect->get($key);
        }

        return new Collection(array_values($results));
    }

    /**
     * Get a Collator object for the locale or current locale.
     */
    public static function getCollator(?string $locale = null): Collator
    {
        static $collators = [];

        $locale ??= App::getLocale();

        if (! Arr::has($collators, $locale)) {
            $collator = new Collator($locale);

            if (self::currentLang($locale) === 'fr') {
                $collator->setAttribute(Collator::FRENCH_COLLATION, Collator::ON);
            }

            $collators[$locale] = $collator;

            return $collator;
        }

        return $collators[$locale];
    }

    /**
     * Get a value retrieving callback.
     */
    private static function valueRetriever(callable|string $value): callable
    {
        return is_callable($value) ? $value : fn ($item) => data_get($item, $value);
    }

    /**
     * Get the current language from locale.
     */
    private static function currentLang(string $locale): string
    {
        if (preg_match('/(-|_)/', $locale)) {
            $locale = preg_split('/(-|_)/', $locale, 2)[0];
        }

        return mb_strtolower($locale);
    }
}
