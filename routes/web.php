<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

Route::get('/images/{path}', [MediaController::class, 'publicImage'])->where('path', '.*');

Route::get(
    '/{any?}',
    function () {
        return view('app');
    }
)->where('any', '^(?!api\/)[\/\w\.\,-]*');
