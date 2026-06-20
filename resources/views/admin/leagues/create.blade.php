@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create League" />
    @include('admin.leagues._form', ['action' => route('admin.leagues.store')])
@endsection
