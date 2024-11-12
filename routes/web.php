<?php

use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('location', [LocationController::class, 'location']);
// Route::post('update-location', [DeliveryController::class, 'update']);
