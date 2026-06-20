@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Color" />
    @include('admin.colors._form', ['color' => $color, 'action' => route('admin.colors.update', $color), 'method' => 'PUT'])
@endsection
