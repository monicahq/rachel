<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Auth;
use Override;

trait ResolvesModelInAccount
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    #[Override]
    public function resolveRouteBinding($value, $field = null)
    {
        $field ??= $this->getRouteKeyName();

        return $this->where('account_id', Auth::user()->account->id)
            ->where(fn ($query) => $query
                ->where($field, $value)
                ->orWhere('id', $value)
            )
            ->firstOrFail();
    }
}
