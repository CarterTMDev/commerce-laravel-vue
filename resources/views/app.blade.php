@extends('layouts.base')

@section('title', 'Customers')

@section('content')

{{-- TODO: create "Add Customer" button --}}
<customers-grid :customer="{{ Js::from((new \App\Models\Customer())->makeEmpty()); }}"></customers-grid>

@endsection