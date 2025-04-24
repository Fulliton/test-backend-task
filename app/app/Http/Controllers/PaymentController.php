<?php

namespace App\Http\Controllers;

use App\Services\Payments\GatewayFactory;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @throws \Exception
     */
    public function callback(Request $request): void
    {
        try {
            $gateway = GatewayFactory::create($request);
            $gateway->process();
        } catch (\Exception $exception) {
            throw new $exception;
        }
    }
}
