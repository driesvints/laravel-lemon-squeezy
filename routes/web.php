<?php

use Illuminate\Support\Facades\Route;
use LaravelLemonSqueezy\Http\Controllers\WebhookController;

Route::post('webhook', WebhookController::class)->name('webhook');
