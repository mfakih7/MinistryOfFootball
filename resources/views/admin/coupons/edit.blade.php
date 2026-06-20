@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Edit Coupon" />
    @include('admin.coupons._form', ['coupon' => $coupon, 'action' => route('admin.coupons.update', $coupon), 'method' => 'PUT'])
@endsection
