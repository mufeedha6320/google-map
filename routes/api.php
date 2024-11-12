<?php

use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\WeatherApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// POSTMAN
// Google map
Route::get('get', [DeliveryController::class, 'index']);
// Route::get('location', [DeliveryController::class, 'location']);
Route::post('update-location', [DeliveryController::class, 'update']);



// Weather Map
Route::get('index', [WeatherApiController::class, 'index']);
Route::get('weather', [WeatherApiController::class, 'getWeather']);
