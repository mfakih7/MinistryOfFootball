@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Team" />
    @include('admin.teams._form', ['action' => route('admin.teams.store'), 'leagues' => $leagues])
@endsection
