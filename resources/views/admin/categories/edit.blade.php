@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Category" />
    @include('admin.categories._form', ['category' => $category, 'action' => route('admin.categories.update', $category), 'method' => 'PUT'])
@endsection
