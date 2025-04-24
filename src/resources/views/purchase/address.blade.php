@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection


@section('content')
    <div class="container">
        <h2 class="title">住所の変更</h2>

        <form class='form-group' action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post">
            @csrf
            <div class="form-label">郵便番号</div>
            <input class="form_text" type="text" id="postal_code" name="postal_code"
                value="{{ old('postal_code', $shippingAddress['postal_code']) }}">

            <div class="form-error">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">住所</div>
            <input class="form_text" type="text" id="address" name="address" value="{{ old('address', $shippingAddress['address'])  }}">
            <div class="form-error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">建物名</div>
            <input class="form_text" type="text" id="building" name="building" value="{{ old('building', $shippingAddress['building'] ?? '') }}">

            <button class="form_button" type="submit">更新する</button>
        </form>
    </div>

@endsection