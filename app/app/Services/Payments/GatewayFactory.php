<?php

namespace App\Services\Payments;

use Illuminate\Http\Request;

class GatewayFactory
{
    public static function create(Request $request): GatewayHandler
    {
        if ($request->has(['merchant_id', 'payment_id', 'sign'])) {
            $sign = $request->get('sign');
            $payload = $request->except(['sign']);
            return new BarGateway($sign, $payload);
        }

        if ($request->has(['project', 'invoice'])) {
            $sign = $request->header('Authorization');
            $payload = $request->all();

            return new FooGateway($sign, $payload);
        }

        throw new \RuntimeException('Gateway handler not implemented');
    }
}
