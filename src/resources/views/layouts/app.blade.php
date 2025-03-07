<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="Top-page_header">
            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHアイコン"></a>

            <form action="/search" method="get">
                @csrf
                <input class="Top_page_header_search" type="text" name="query" placeholder="何をお探しですか？"></input>
            </form>

            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="button">ログアウト</button>
                </form>
            @endauth

            <a href="/mypage" class="button">マイページ</a>
            <a href="/sell" class="button">出品</a>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>