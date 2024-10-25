<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::fallback(function () {
    return response(null, 403);
});
