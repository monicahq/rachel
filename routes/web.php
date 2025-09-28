<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory => view('welcome'));
