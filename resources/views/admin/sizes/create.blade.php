@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Size" />
    @include('admin.sizes._form', ['action' => route('admin.sizes.store')])
@endsection
