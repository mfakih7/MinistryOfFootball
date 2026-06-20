@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Category" />
    @include('admin.categories._form', ['action' => route('admin.categories.store')])
@endsection
