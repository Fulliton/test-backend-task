<?php

namespace App\Services\Payments;

interface GatewayHandlerInterface
{
    public function process(): void;
}
