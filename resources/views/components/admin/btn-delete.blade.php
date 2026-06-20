@props([
    'action',
    'label' => 'Delete',
    'item' => 'this item',
])

<div x-data="{ open: false }" class="inline-flex">
    <button type="button" @click="open = true" {{ $attributes->merge(['class' => 'admin-action-btn admin-action-btn-delete']) }}>
        <x-admin.icon.trash />
        <span>{{ $label }}</span>
    </button>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-modal-title"
    >
        <div x-show="open" x-transition.opacity class="absolute inset-0 bg-black/50" @click="open = false"></div>

        <div
            x-show="open"
            x-transition
            class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
            @keydown.escape.window="open = false"
        >
            <h3 id="delete-modal-title" class="text-lg font-bold text-gray-900">Confirm Delete</h3>
            <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete this item?</p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" @click="open = false" class="admin-btn-secondary w-full sm:w-auto">
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-btn-danger w-full sm:w-auto">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
