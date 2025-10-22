<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Override;

trait ResolvesModelInAccount
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Model|null
     */
    #[Override]
    public function resolveRouteBinding($value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        try {
            return $this->resolveRouteBindingByField($value, $field);
        } catch (ModelNotFoundException $e) {
            if ($field !== 'id') {
                return $this->resolveRouteBindingById($value);
            }

            return $e;

        }
    }

    private function resolveRouteBindingByField(mixed $value, string $field): Model
    {
        return $this->where([
            $field => $value,
            'account_id' => Auth::user()->account->id,
        ])->firstOrFail();
    }

    private function resolveRouteBindingById(mixed $value): Model
    {
        return $this->where([
            'id' => $value,
            'account_id' => Auth::user()->account->id,
        ])->firstOrFail();
    }
}
