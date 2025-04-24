<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;

class BarGateway extends GatewayHandler
{
    protected string $gatewayGuid = 'bar';

    public function verify(): bool
    {
        $payload = $this->payload;
        ksort($payload);
        $signString = implode(':', $payload);
        $signString = $signString . $this->gateway->key;

        return $this->sign === hash('sha256', $signString);
    }

    protected function formatPayload(): array
    {
        return [
            'user_id' => $this->payload['merchant_id'],
            'payment_id' => $this->payload['payment_id'],
            'status' => PaymentStatus::create($this->payload['status'])->value,
            'amount' => $this->payload['amount'],
        ];
    }
}
