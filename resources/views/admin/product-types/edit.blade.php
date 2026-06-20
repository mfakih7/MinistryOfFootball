@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Product Type" />
    @include('admin.product-types._form', ['productType' => $productType, 'action' => route('admin.product-types.update', $productType), 'method' => 'PUT'])
@endsection
