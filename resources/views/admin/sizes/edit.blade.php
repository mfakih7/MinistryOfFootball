@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Size" />
    @include('admin.sizes._form', ['size' => $size, 'action' => route('admin.sizes.update', $size), 'method' => 'PUT'])
@endsection
