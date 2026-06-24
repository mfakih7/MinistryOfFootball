<?php

namespace App\Http\Requests;

use App\Services\CartService;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'customizations' => ['nullable', 'array'],
            'customizations.*.requested' => ['nullable', 'boolean'],
        ];

        foreach (app(CartService::class)->items() as $item) {
            if (! $item['is_customizable']) {
                continue;
            }

            $rules["customizations.{$item['key']}.details"] = [
                'nullable',
                'string',
                'max:500',
                "required_if:customizations.{$item['key']}.requested,1",
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'customizations.*.details.required_if' => 'Please enter the customization details (name/number) for this item.',
        ];
    }
}
