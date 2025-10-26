<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

final class TokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            'device_name' => 'required',
        ]);

        $token = $request->user()
            ->createToken($validated['device_name'])
            ->plainTextToken;

        return Response::json(
            data: Str::of($token)->after('|'),
            status: 201,
        );
    }
}
