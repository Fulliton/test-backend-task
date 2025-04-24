<?php

namespace App\Services\Payments;

use App\Models\Gateway;
use App\Models\Payment;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class GatewayHandler implements GatewayHandlerInterface
{
    protected string $gatewayGuid = '';

    protected Gateway $gateway;

    public function __construct(protected string $sign, protected array $payload)
    {
        if (!$this->gatewayGuid) {
            throw new \RuntimeException('Gateway guid not set');
        }

        $gateway = Gateway::query()
            ->where('guid', $this->gatewayGuid)
            ->first();

        if (!$gateway) {
            throw new NotFoundHttpException('Gateway not found');
        }

        $this->gateway = $gateway;
    }

    public function process(): void
    {
        if (!$this->verify()) {
            throw new BadRequestHttpException();
        }

        if (!$this->checkLockTime()) {
            throw new LockedHttpException();
        }

        $this->updatePayment();
        $this->checkLimit();
    }

    abstract public function verify(): bool;

    public function checkLockTime(): bool
    {
        $now = Carbon::now();

        if ($now->diffInDays($this->gateway->lock_time) > 1) {
            return true;
        }

        return false;
    }

    public function updatePayment(): void
    {
        $payload = $this->formatPayload();
        $payment = Payment::query()
            ->where('gateway_id', $this->gateway->id)
            ->where('user_id', $payload['user_id'])
            ->where('id', $payload['payment_id'])
            ->first();

        if (!$payment) {
            throw new NotFoundHttpException('Payment not found');
        }

        $payment->update([
            'status' => $payload['status'],
            'amount' => $payload['amount'],
        ]);
    }

    public function checkLimit(): void
    {
        // todo не понял как должен работать limit
        $sum = Payment::query()
            ->where('gateway_id', $this->gateway->id)
            ->where('created_at', '>', $this->gateway->lock_time)
            ->sum('amount');

        if ($sum >= $this->gateway->limit) {
            $this->gateway->update(['lock_time' => now()]);
        }
    }

    abstract protected function formatPayload(): array;
}
