@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection


@section('content')
    <div class="container">
        <h2 class="title">プロフィール設定</h2>

        <form class='form-group' action="/mypage/profile" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="image_container">
                <div class="image_output_wrap">
                    @if ($profile->image)
                        <img class="profile_image" id="previewImage" src="{{ asset('storage/' . $profile->image) }}" alt="">
                    @else
                        <img class="profile_image" id="previewImage" style="display: none;" alt="">
                    @endif
                    <input class="file" type="file" id="imageInput" name="image" accept="image/*" hidden>
                </div>

                <label for="imageInput" class="file-label">画像を選択する</label>
            </div>

            <div class="form-error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">ユーザー名</div>
            <input class="text_form" type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}">

            <div class="form-error">
                @error('name')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">郵便番号</div>
            <input class="text_form" type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            <div class="form-error">
                @error('postal_code')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">住所</div>
            <input class="text_form" type="text" id="address" name="address" value="{{ old('address', $profile->address ?? '')  }}">
            <div class="form-error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">建物名</div>
            <input class="text_form" type="text" id="building" name="building" value="{{ old('building', $profile->building ?? '') }}">

            <button class="form_button" type="submit">更新する</button>

        </form>
    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImage');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

@endsection