@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Product Type" />
    @include('admin.product-types._form', ['action' => route('admin.product-types.store')])
@endsection
