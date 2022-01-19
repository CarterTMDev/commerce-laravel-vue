@extends('layouts.base')

@section('title', 'Orders')

@section('content')

<orders-grid :customer-id="{{ $customerId }}" :order="{{ Js::from((new \App\Models\Order())->makeEmpty()); }}"></orders-grid>

@endsection