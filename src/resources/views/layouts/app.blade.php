<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
    @livewireStyles
</head>

<body>
    <header class="header">
        <div class="Top-page_header">
            <div class="logo">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHアイコン">
                </a>
            </div>

            <div class="search_button_row">
                <div class="Top_page_header_search_container">
                    <form action="/search" method="get">
                    @csrf
                    <input class="Top_page_header_search" type="text" name="query" placeholder=" なにをお探しですか？ " value="{{ request('query') }}">
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                    </form>
                </div>

                <div class="buttons">
                    @if(Auth::check())
                        <form action="{{ route('logout') }}" method="POST">
                        @csrf
                            <button type="submit" class="nav_button">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav_button">ログイン</a>
                    @endif
                    <a href="/mypage" class="nav_button">マイページ</a>
                    <a href="/sell" class="sell_button">出品</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>

</html>