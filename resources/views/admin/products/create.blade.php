@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Product" />
    @include('admin.products._form', [
        'action' => route('admin.products.store'),
        'categories' => $categories,
        'leagues' => $leagues,
        'teams' => $teams,
        'productTypes' => $productTypes,
        'sizes' => $sizes,
        'colors' => $colors,
    ])
@endsection
