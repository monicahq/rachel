<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

final class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('abilities:read');
    }

    /**
     * Retrieve the authenticated user.
     *
     * Get the authenticated user.
     */
    #[ResponseFromApiResource(JsonResource::class, User::class)]
    #[Response(['message' => 'User not found'], status: 404, description: 'User not found')]
    public function user(Request $request): JsonResource
    {
        return new JsonResource($request->user());
    }

    /**
     * Retrieve a user.
     *
     * Get a specific user object.
     */
    #[ResponseFromApiResource(JsonResource::class, User::class)]
    public function show(Request $request, User $user): JsonResource
    {
        return new JsonResource($user);
    }

    /**
     * List all users.
     *
     * Get all the users in the account.
     */
    #[ResponseFromApiResource(JsonResource::class, User::class, collection: true)]
    public function index(Request $request)
    {
        $users = $request->user()->account->users();

        return JsonResource::collection($users);
    }
}
