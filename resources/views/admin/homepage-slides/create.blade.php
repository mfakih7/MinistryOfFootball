@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Homepage Slide" />
    @include('admin.homepage-slides._form', ['action' => route('admin.homepage-slides.store')])
@endsection
