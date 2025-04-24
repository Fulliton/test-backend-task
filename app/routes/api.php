<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('/callback_url/', [PaymentController::class, 'callback']);
