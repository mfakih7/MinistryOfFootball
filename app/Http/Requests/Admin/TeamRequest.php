<?php

namespace App\Http\Requests\Admin;

use App\Models\Team;
use App\Support\SlugGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('team')?->id;

        return [
            'league_id' => ['nullable', 'exists:leagues,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('teams', 'slug')->ignore($id)],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'country' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => $this->input('sort_order', 0),
        ]);

        if (! $this->filled('slug') && $this->filled('name')) {
            $this->merge(['slug' => SlugGenerator::unique($this->input('name'), Team::class, $this->route('team')?->id)]);
        }
    }
}
