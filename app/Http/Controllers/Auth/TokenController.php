<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $this->validate($request, [
            'code' => 'required|string',
            'code_verifier' => 'required|string',
        ]);

        $authCode = null;

        try {
            $authCode = $this->getAuthCode($validated['code']);

            $this->validateChallenge($authCode, $validated['code_verifier']);

            return $this->generateToken($authCode, $request);
        } finally {
            $authCode?->delete();
        }
    }

    private function getAuthCode(string $code): AuthCode
    {
        return AuthCode::where('code', $code)
            ->firstOrFail();
    }

    private function validateChallenge(AuthCode $authCode, string $codeVerifier): void
    {
        $codeChallenge = strtr(mb_rtrim(
            base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');

        abort_unless(
            $authCode->code_challenge === $codeChallenge
            && $authCode->expires_at >= now(),
            403,
        );
    }

    private function generateToken(AuthCode $authCode, Request $request): JsonResponse
    {
        $token = $authCode->user
            ->createToken($request->header('User-Agent'), ['*'], now()->addYears(1))
            ->plainTextToken;

        return response()->json(
            data: [
                'token_type' => 'Bearer',
                'access_token' => explode('|', (string) $token, 2)[1],
                'expires_in' => 365 * 24 * 60 * 60,
            ],
            status: 201,
        );
    }
}
