<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::apiResource('products', ProductController::class);


Route::post('products/{product}/buy', [ProductController::class, 'buy']);

Route::post('/products/{id}/purchase', [ProductController::class, 'purchase']);

Route::get('/test', function () {
    return 'API is working';
});
