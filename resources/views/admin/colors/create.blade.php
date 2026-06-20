@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Color" />
    @include('admin.colors._form', ['action' => route('admin.colors.store')])
@endsection
