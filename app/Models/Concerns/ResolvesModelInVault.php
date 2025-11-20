<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Vault;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Override;

trait ResolvesModelInVault
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

        $vault = $this->getVault();

        try {
            return $this->resolveRouteBindingByField($value, $field, $vault);
        } catch (ModelNotFoundException $e) {
            if ($field !== 'id') {
                try {
                    return $this->resolveRouteBindingById($value, $vault);
                } catch (Exception) {
                    throw $e;
                }
            }

            throw $e;
        }
    }

    private function resolveRouteBindingByField(mixed $value, string $field, Vault $vault): Model
    {
        return $this->where([
            $field => $value,
            'vault_id' => $vault->id,
        ])->firstOrFail();
    }

    private function resolveRouteBindingById(mixed $value, Vault $vault): Model
    {
        return $this->where([
            'id' => $value,
            'vault_id' => $vault->id,
        ])->firstOrFail();
    }

    private function getVault(): Vault
    {
        $vault = Route::current()->parameter('vault');

        throw_unless($vault !== null && $vault->account_id === Auth::user()->account_id, ModelNotFoundException::class);

        return $vault;
    }
}
