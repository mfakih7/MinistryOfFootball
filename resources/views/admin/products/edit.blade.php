@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Product" />
    @include('admin.products._form', [
        'product' => $product,
        'action' => route('admin.products.update', $product),
        'method' => 'PUT',
        'categories' => $categories,
        'leagues' => $leagues,
        'teams' => $teams,
        'productTypes' => $productTypes,
        'sizes' => $sizes,
        'colors' => $colors,
    ])
@endsection
