@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    @livewire('purchase-form', [
        'item' => $item,
        'profile' => $profile,
        'shippingAddress' => $shippingAddress
    ])
@endsection