<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Contact;
use App\Models\Vault;
use App\Services\CreateContact;
use App\Services\DestroyContact;
use App\Services\UpdateContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Contact management
 *
 * @subgroup Contacts
 */
final class ContactController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Contact::class);
        $this->middleware('abilities:read')->only(['index', 'show']);
        $this->middleware('abilities:write')->only(['store', 'update', 'delete']);
    }

    /**
     * List all contacts.
     */
    #[ResponseFromApiResource(JsonResource::class, Contact::class, collection: true)]
    public function index(Vault $vault): JsonResource
    {
        $contacts = $vault->contacts;

        return JsonResource::collection($contacts);
    }

    /**
     * Retrieve a Contact.
     */
    #[ResponseFromApiResource(JsonResource::class, Contact::class)]
    public function show(Vault $vault, Contact $contact): JsonResource
    {
        return new JsonResource($contact);
    }

    /**
     * Create a Contact.
     */
    #[ResponseFromApiResource(JsonResource::class, Contact::class, status: 201)]
    #[BodyParam('name', description: 'The name of the contact. Max 255 characters.')]
    public function store(Request $request, Vault $vault): JsonResource
    {
        $validated = $this->validate($request, Contact::rules());

        $contact = (new CreateContact(
            vault: $vault,
            name: $validated['name'],
        ))->execute();

        return new JsonResource($contact);
    }

    /**
     * Update a Contact.
     */
    #[ResponseFromApiResource(JsonResource::class, Contact::class)]
    #[BodyParam('name', description: 'The name of the contact. Max 255 characters.')]
    public function update(Request $request, Vault $vault, Contact $contact): JsonResource
    {
        $validated = $this->validate($request, Contact::rules());

        $contact = (new UpdateContact(
            contact: $contact,
            vault: $vault,
            name: $validated['name'],
        ))->execute();

        return new JsonResource($contact);
    }

    /**
     * Destroy a Contact.
     */
    #[Response(status: 204)]
    public function destroy(Vault $vault, Contact $contact): JsonResponse
    {
        (new DestroyContact(
            contact: $contact
        ))->execute();

        return new JsonResponse(status: 204);
    }
}
