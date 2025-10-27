<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vault;
use App\Services\CreateVault;
use App\Services\DestroyVault;
use App\Services\UpdateVault;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class VaultController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Vault::class);
        $this->middleware('abilities:read')->only(['index', 'show']);
        $this->middleware('abilities:write')->only(['store', 'update', 'delete']);
    }

    /**
     * List all vaults.
     */
    public function index(Request $request): JsonResource
    {
        $vaults = $request->user()->account->vaults;

        return JsonResource::collection($vaults);
    }

    /**
     * Retrieve a vault.
     */
    public function show(Request $request, Vault $vault): JsonResource
    {
        return new JsonResource($vault);
    }

    /**
     * Create a vault.
     */
    public function store(Request $request): JsonResource
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $vault = (new CreateVault(
            $request->user()->account,
            $validated['name'],
            $validated['description'],
        ))->execute();

        return new JsonResource($vault);
    }

    /**
     * Update a vault.
     */
    public function update(Request $request, Vault $vault): JsonResource
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $vault = (new UpdateVault(
            $vault,
            $validated['name'],
            $validated['description'],
        ))->execute();

        return new JsonResource($vault);
    }

    /**
     * Destroy a vault.
     */
    public function destroy(Request $request, Vault $vault): JsonResource
    {
        (new DestroyVault(
            $vault
        ))->execute();

        return new JsonResource([]);
    }
}
