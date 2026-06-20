@extends('layouts.app')

@section('content')
    <div class="container-store py-8 lg:py-12">
        <div class="mx-auto max-w-3xl">
            <h1 class="section-title mb-2">Contact Us</h1>
            <p class="mb-8 text-gray-600">Have a question about an order, product, or customization? Send us a message and we will reply as soon as possible.</p>

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                        @csrf
                        @if ($errors->any())
                            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message *</label>
                            <textarea id="message" name="message" rows="6" required class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:outline-none focus:ring-1 focus:ring-brand-red">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary">Send Message</button>
                    </form>
                </div>

                <div class="space-y-4 rounded-xl border border-gray-200 bg-gray-50 p-6 text-sm">
                    <h2 class="font-bold text-gray-900">Store Info</h2>
                    @if ($storeSettings['store_phone'] ?? null)
                        <p><span class="text-gray-500">Phone:</span> {{ $storeSettings['store_phone'] }}</p>
                    @endif
                    @if ($storeSettings['store_email'] ?? null)
                        <p><span class="text-gray-500">Email:</span> {{ $storeSettings['store_email'] }}</p>
                    @endif
                    @if ($storeSettings['store_address'] ?? null)
                        <p><span class="text-gray-500">Address:</span> {{ $storeSettings['store_address'] }}</p>
                    @endif
                    @if ($whatsappFloatUrl ?? null)
                        <a href="{{ $whatsappFloatUrl }}" target="_blank" rel="noopener" class="btn-primary mt-4 inline-flex w-full justify-center">WhatsApp Us</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
