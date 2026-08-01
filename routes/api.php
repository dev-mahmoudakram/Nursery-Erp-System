<?php

use App\Http\Controllers\Api\BatchApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\NurseryApiController;
use Illuminate\Support\Facades\Route;

// يفترض middleware => ['auth:sanctum'] لاحقًا حسب وثيقة Security & Infrastructure
Route::prefix('v1')->group(function () {
    Route::apiResource('nurseries', NurseryApiController::class)->only(['index', 'show']);
    Route::apiResource('items', ItemApiController::class)->only(['index', 'show']);
    Route::apiResource('batches', BatchApiController::class)->only(['index', 'show']);

    Route::get('batches/qr/{qr}', [BatchApiController::class, 'findByQr']);
    Route::post('batches/{batch}/status', [BatchApiController::class, 'changeStatus']);
    Route::post('inventory-movements/sync', [BatchApiController::class, 'syncMovements']);
});
