@extends('layouts.base')

@section('title', 'Order')

@section('content')

<order-report :order-id="{{ $orderId }}"></order-report>

@endsection