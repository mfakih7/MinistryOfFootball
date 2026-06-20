@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Create Coupon" />
    @include('admin.coupons._form', ['action' => route('admin.coupons.store')])
@endsection
