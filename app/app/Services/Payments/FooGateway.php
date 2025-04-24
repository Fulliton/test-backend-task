<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;

class FooGateway extends GatewayHandler
{
    protected string $gatewayGuid = 'foo';

    public function verify(): bool
    {
        $payload = $this->payload;
        ksort($payload);
        $signString = implode('.', $payload);
        $signString = $signString . $this->gateway->key;
        return $this->sign === hash('md5', $signString);
    }

    protected function formatPayload(): array
    {
        return [
            'user_id' => $this->payload['project'],
            'payment_id' => $this->payload['invoice'],
            'status' => PaymentStatus::create($this->payload['status'])->value,
            'amount' => $this->payload['amount'],
        ];
    }
}
