<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{ config('app.name') }}</title>
    @if (! empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-brand-black px-4">
    <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-xl">
        <div class="mb-8 text-center">
            @if ($adminLogoUrl ?? null)
                <img src="{{ $adminLogoUrl }}" alt="{{ $brandingStoreName ?? config('app.name') }}" class="mx-auto h-12 max-w-[220px] object-contain">
            @else
                <h1 class="text-2xl font-bold text-brand-black">{{ $brandingStoreName ?? 'Ministry Of Football' }}</h1>
            @endif
            <p class="mt-2 text-sm text-gray-500">Admin Panel Login</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="admin-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="admin-input">
            </div>
            <div>
                <label for="password" class="admin-label">Password</label>
                <input type="password" id="password" name="password" required class="admin-input">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                Remember me
            </label>
            <button type="submit" class="admin-btn-primary w-full">Sign In</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="text-brand-red hover:underline">← Back to store</a>
        </p>
    </div>
</body>
</html>
