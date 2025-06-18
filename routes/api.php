<?php

use Illuminate\Support\Facades\Route;
use Modules\Schooling\Http\Controllers\SchoolingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('schoolings', SchoolingController::class)->names('schooling');
});
