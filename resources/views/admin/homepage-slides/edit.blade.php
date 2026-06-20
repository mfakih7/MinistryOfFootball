@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Homepage Slide" />
    @include('admin.homepage-slides._form', ['slide' => $slide, 'action' => route('admin.homepage-slides.update', $slide), 'method' => 'PUT'])
@endsection
