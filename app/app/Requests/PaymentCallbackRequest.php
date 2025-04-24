<?php

namespace App\Requests;

use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentCallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::enum(PaymentStatus::class)
            ],
            'amount' => [
                'required',
                'integer',
            ]
        ];
    }
}
