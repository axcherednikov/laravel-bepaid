<?php

use Excent\BePaidLaravel\Http\Controllers\BePaidController;
use Illuminate\Support\Facades\Route;

$config = config('bepaid.urls');

Route::post($config['notifications']['path'], [BePaidController::class, 'notification'])
    ->middleware(array_unique(array_merge(['bepaid.inject_basic_auth'], config('bepaid.middlewares'))))
    ->name($config['notifications']['name']);

Route::middleware(config('bepaid.middlewares'))->group(function () use ($config) {
    Route::get($config['cancel']['path'], [BePaidController::class, 'cancel'])->name($config['cancel']['name']);
    Route::get($config['decline']['path'], [BePaidController::class, 'decline'])->name($config['decline']['name']);
    Route::get($config['success']['path'], [BePaidController::class, 'success'])->name($config['success']['name']);
    Route::get($config['fail']['path'], [BePaidController::class, 'fail'])->name($config['fail']['name']);
    Route::get($config['return']['path'], [BePaidController::class, 'return',])->name($config['return']['name']);
});
