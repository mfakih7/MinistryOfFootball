@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Team" />
    @include('admin.teams._form', ['team' => $team, 'action' => route('admin.teams.update', $team), 'method' => 'PUT', 'leagues' => $leagues])
@endsection
