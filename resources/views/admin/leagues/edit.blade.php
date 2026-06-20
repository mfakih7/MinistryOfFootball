@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit League" />
    @include('admin.leagues._form', ['league' => $league, 'action' => route('admin.leagues.update', $league), 'method' => 'PUT'])
@endsection
