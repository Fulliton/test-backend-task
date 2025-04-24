<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';

    public static function create(string $status): PaymentStatus
    {
        return match ($status) {
            'new', 'created' => self::NEW,
            'pending', 'inprogress' => self::PENDING,
            'completed', 'paid' => self::COMPLETED,
            'expired' => self::EXPIRED,
            'rejected' => self::REJECTED,
        };
    }
}
