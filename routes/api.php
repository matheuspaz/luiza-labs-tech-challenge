<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InterestPointController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login');
});

Route::group(['prefix' => 'interest-points', 'middleware' => 'auth:sanctum'], function () {
    Route::post('', [InterestPointController::class, 'create'])->name('interest-point.create');
    Route::get('', [InterestPointController::class, 'list'])->name('interest-point.list');
});
