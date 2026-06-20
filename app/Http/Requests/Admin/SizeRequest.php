<?php

namespace App\Http\Requests\Admin;

use App\Models\Size;
use App\Support\SlugGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('size')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('sizes', 'slug')->ignore($id)],
            'sort_order' => ['integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);

        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge(['slug' => SlugGenerator::unique($this->input('name'), Size::class, $this->route('size')?->id)]);
        }
    }
}
