<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class TokenController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $token = $request->user()
            ->createToken($request->user()->name, ['*'], now()->addYears(1))
            ->plainTextToken;

        return redirect(
            to: $request->session()->pull('redirect_uri'),
            status: 201,
            headers: ['token' => explode('|', (string) $token, 2)[1]],
            secure: true,
        );
    }
}
