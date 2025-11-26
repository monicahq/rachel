<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

abstract class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Log::info('API Request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
            ]);

            return $next($request);
        });
    }
}
