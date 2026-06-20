@extends('layouts.app')

@section('content')
    <div class="container-store py-12 lg:py-16">
        <article class="mx-auto max-w-3xl">
            <h1 class="section-title">{{ $title }}</h1>
            <div class="prose prose-gray mt-8 max-w-none">
                @if ($content)
                    {!! nl2br(e($content)) !!}
                @else
                    <p class="text-gray-600">This page content has not been configured yet. Please check back soon or <a href="{{ route('contact') }}" class="text-brand-red hover:underline">contact us</a>.</p>
                @endif
            </div>
        </article>
    </div>
@endsection
