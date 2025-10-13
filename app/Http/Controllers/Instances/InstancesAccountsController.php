<?php

declare(strict_types=1);

namespace App\Http\Controllers\Instances;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class InstancesAccountsController extends Controller
{
    public function show(): View
    {
        return view('instances.accounts.show');
    }
}
