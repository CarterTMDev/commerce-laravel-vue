@extends('layouts.base')

@section('title', 'Customers')

@section('content')

<customers-grid></customers-grid>
{{-- TODO: create "Add Customer" button --}}
<customer-edit-modal :customer="{{ Js::from((new \App\Models\Customer())->makeEmpty()); }}"></customer-edit-modal>

@endsection