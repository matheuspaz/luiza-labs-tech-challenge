<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InterestPointController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login');
});
