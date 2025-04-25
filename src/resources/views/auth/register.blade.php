<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <header class="header">
        <div class="Top-page_header">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHアイコン"></a>
    </header>

    <div class="container">
        <h2>会員登録</h2>

        <form class='form-group' action="/register" method="post">
            @csrf
            <div class="form-label">ユーザー名</div>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
            <div class="form-error">
                @error('name')
                {{ $message }}
                @enderror
            </div>

            <div class="form-label">メールアドレス</div>
            <input type="text" id="email" name="email" value="{{ old('email') }}">
            <div class="form-error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>

            <div class="form-label">パスワード</div>
            <input type="password" id="password" name="password">
            <div class="form-error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>

            <div class='form-label'>確認用パスワード</div>
            <input type="password" name="password_confirmation" >
            <div class="form-error">
                @error('password_confirmation')
                    {{ $message }}
                @enderror
            </div>

            <button class="form_button" type="submit">登録する</button>

            <a class="link_login" href="/login">ログインはこちら</a>

        </form>
    </div>
</body>
</html>