@extends('layouts.base')

@section('title', 'Customers')

@section('content')

<customers-grid :customer="{{ Js::from((new \App\Models\Customer())->makeEmpty()); }}"></customers-grid>

@endsection