<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InterestPointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login');
});

Route::group(['prefix' => 'interest-points', 'middleware' => 'auth:sanctum'], function () {
    Route::post('', [InterestPointController::class, 'create'])->name('interest-point.create');
});
