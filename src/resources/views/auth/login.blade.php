<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <header class="header">
        <div class="Top-page_header">
            <a href="/">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHアイコン">
            </a>
        </div>
    </header>

    <div class="container">
        <h2>ログイン</h2>

        <form class='form-group' action="/login" method="post">
            @csrf
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

            <button class="form_button" type="submit">ログインする</button>

            <a class="link_login" href="/register">会員登録はこちら</a>

        </form>
    </div>
</body>

</html>