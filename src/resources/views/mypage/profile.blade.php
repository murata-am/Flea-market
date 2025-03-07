@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection


@section('content')
    <div class="container">
        <h2 class="title">プロフィール設定</h2>

        <form class='form-group' action="/update" method="post">
            @csrf
            <output id="list" class="image_output"></output>

            <label for="image" class="file-label">画像を選択する</label>
            <input class="file" type="file" id="image" name="image" accept="image/*" hidden>

            <div class="form-error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">ユーザー名</div>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            <div class="form-error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">郵便番号</div>
            <input type="text" id="postal_code" name="postal_code">
            <div class="form-error">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">住所</div>
            <input type="text" id="address" name="address">
            <div class="form-error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">建物名</div>
            <input type="text" id="building" name="building">
            <div class="form-error">
                @error('building')
                    {{ $message }}
                @enderror
            </div>

            <button class="form_button" type="submit">更新する</button>

        </form>
    </div>

    <script>
    document.getElementById("image").addEventListener("change", function(event) {
    let output = document.getElementById("list");
    output.innerHTML = ""; // 既存の画像をクリア

    let file = event.target.files[0];
    if (file) {
        let img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        output.appendChild(img);
    }
    });
    </script>

@endsection