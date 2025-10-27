<?php

declare(strict_types=1);

namespace App\Http\Controllers\Instances;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class InstancesController extends Controller
{
    public function index(): View
    {
        return view('instances.index');
    }
}
