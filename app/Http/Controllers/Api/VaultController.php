<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vault;
use App\Services\CreateVault;
use App\Services\DestroyVault;
use App\Services\UpdateVault;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Vault management
 *
 * @subgroup Vaults
 */
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
    #[ResponseFromApiResource(JsonResource::class, Vault::class, collection: true)]
    public function index(Request $request): JsonResource
    {
        $vaults = $request->user()->account->vaults;

        return JsonResource::collection($vaults);
    }

    /**
     * Retrieve a vault.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class)]
    public function show(Request $request, Vault $vault): JsonResource
    {
        return new JsonResource($vault);
    }

    /**
     * Create a vault.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class, status: 201)]
    #[BodyParam('name', description: 'The name of the vault. Max 255 characters.')]
    #[BodyParam('description', description: 'The description of the vault. Max 65535 characters.', required: false)]
    public function store(Request $request): JsonResource
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $vault = (new CreateVault(
            user: $request->user(),
            name: $validated['name'],
            description: $validated['description'],
        ))->execute();

        return new JsonResource($vault);
    }

    /**
     * Update a vault.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class)]
    #[BodyParam('name', description: 'The name of the vault. Max 255 characters.')]
    #[BodyParam('description', description: 'The description of the vault. Max 65535 characters.', required: false)]
    public function update(Request $request, Vault $vault): JsonResource
    {
        $validated = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $vault = (new UpdateVault(
            vault: $vault,
            user: $request->user(),
            name: $validated['name'],
            description: $validated['description'],
        ))->execute();

        return new JsonResource($vault);
    }

    /**
     * Destroy a vault.
     */
    #[Response(status: 204)]
    public function destroy(Request $request, Vault $vault): JsonResponse
    {
        (new DestroyVault(
            vault: $vault
        ))->execute();

        return new JsonResponse(status: 204);
    }
}
