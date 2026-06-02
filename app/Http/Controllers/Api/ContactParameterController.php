<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\Vault;
use App\Services\CreateParameter;
use App\Services\DestroyParameter;
use App\Services\UpdateParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Contact management
 *
 * @subgroup Contact parameters
 */
final class ContactParameterController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('abilities:read')->only(['index', 'show']);
        $this->middleware('abilities:write')->only(['store', 'update', 'destroy']);
    }

    /**
     * List all contact parameters.
     */
    #[ResponseFromApiResource(JsonResource::class, ContactParameter::class, collection: true)]
    public function index(Vault $vault, Contact $contact): JsonResource
    {
        $this->authorize('view', [$contact, $vault]);
        abort_unless($contact->vault_id === $vault->id, 404);

        return JsonResource::collection($contact->parameters);
    }

    /**
     * Retrieve a contact parameter.
     */
    #[ResponseFromApiResource(JsonResource::class, ContactParameter::class)]
    public function show(Vault $vault, Contact $contact, ContactParameter $parameter): JsonResource
    {
        $this->authorize('view', [$contact, $vault]);
        abort_unless($contact->vault_id === $vault->id, 404);

        $parameter = $contact->parameters()->findOrFail($parameter->id);

        return new JsonResource($parameter);
    }

    /**
     * Create a contact parameter.
     */
    #[ResponseFromApiResource(JsonResource::class, ContactParameter::class, status: 201)]
    #[BodyParam('key', description: 'The key of the parameter. Max 32 characters.')]
    #[BodyParam('label', description: 'The display label of the parameter. Max 255 characters.')]
    #[BodyParam('type', description: 'The type of the parameter. Max 255 characters.')]
    #[BodyParam('data', description: 'The value of the parameter.', required: false)]
    public function store(Request $request, Vault $vault, Contact $contact): JsonResource
    {
        $this->authorize('update', [$contact, $vault]);
        abort_unless($contact->vault_id === $vault->id, 404);

        $rules = ContactParameter::rules();
        $validated = $this->validate($request, [
            'key' => $rules['key'],
            'label' => $rules['label'],
            'type' => $rules['type'],
            'data' => $rules['data'],
        ]);

        $parameter = (new CreateParameter(
            contact: $contact,
            key: $validated['key'],
            type: $validated['type'],
            label: $validated['label'],
            data: $validated['data'] ?? null,
        ))->execute();

        return new JsonResource($parameter);
    }

    /**
     * Update a contact parameter.
     */
    #[ResponseFromApiResource(JsonResource::class, ContactParameter::class)]
    #[BodyParam('key', description: 'The key of the parameter. Max 32 characters.')]
    #[BodyParam('label', description: 'The display label of the parameter. Max 255 characters.')]
    #[BodyParam('type', description: 'The type of the parameter. Max 255 characters.')]
    #[BodyParam('data', description: 'The value of the parameter.', required: false)]
    public function update(Request $request, Vault $vault, Contact $contact, ContactParameter $parameter): JsonResource
    {
        $this->authorize('update', [$contact, $vault]);
        abort_unless($contact->vault_id === $vault->id, 404);

        $parameter = $contact->parameters()->findOrFail($parameter->id);

        $rules = ContactParameter::rules();
        $validated = $this->validate($request, [
            'key' => $rules['key'],
            'label' => $rules['label'],
            'type' => $rules['type'],
            'data' => $rules['data'],
        ]);

        $parameter = (new UpdateParameter(
            parameter: $parameter,
            key: $validated['key'],
            label: $validated['label'],
            type: $validated['type'],
            data: $validated['data'] ?? null,
        ))->execute();

        return new JsonResource($parameter);
    }

    /**
     * Destroy a contact parameter.
     */
    #[Response(status: 204)]
    public function destroy(Vault $vault, Contact $contact, ContactParameter $parameter): JsonResponse
    {
        $this->authorize('update', [$contact, $vault]);
        abort_unless($contact->vault_id === $vault->id, 404);

        $parameter = $contact->parameters()->findOrFail($parameter->id);

        (new DestroyParameter(
            parameter: $parameter,
        ))->execute();

        return new JsonResponse(status: 204);
    }
}
