<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'min:6', 'max:30'],
            'order_number' => ['nullable', 'string', 'max:50'],
        ];
    }
}
