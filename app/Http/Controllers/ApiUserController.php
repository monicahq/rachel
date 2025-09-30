<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ApiUserController extends Controller
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
    public function user(Request $request)
    {
        return new JsonResource($request->user());
    }

    /**
     * Retrieve a user.
     *
     * Get a specific user object.
     */
    public function show(Request $request, User $user)
    {
        return new JsonResource($user);
    }

    /**
     * List all users.
     *
     * Get all the users in the account.
     */
    public function index(Request $request)
    {
        $users = $request->user()->account->users();

        return JsonResource::collection($users);
    }
}
