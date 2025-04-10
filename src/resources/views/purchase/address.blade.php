@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection


@section('content')
    <div class="container">
        <h2 class="title">住所の変更</h2>

        <form class='form-group' action="{{ route('address.update', ['item_id' => $item_id]) }}" method="post">
            @csrf
            @method('PATCH')

            <div class="form-label">郵便番号</div>
            <input type="text" id="postal_code" name="postal_code"
                value="{{ old('postal_code', $profile->postal_code ?? '') }}">

            <div class="form-error">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">住所</div>
            <input type="text" id="address" name="address" value="{{ old('address', $profile->address ?? '')  }}">
            <div class="form-error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">建物名</div>
            <input type="text" id="building" name="building" value="{{ old('building', $profile->building ?? '') }}">

            <button class="form_button" type="submit">更新する</button>

        </form>
    </div>

@endsection