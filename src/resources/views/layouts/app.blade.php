<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-marketFlea-market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="Top-page_header">
            <img src="/storage" alt="COACHTECHアイコン"></a>
            <form class="Top_page_header_search" action="" method="get">何をお探しですか？</form>

            <a href="/login" class="button">ログアウト</a>
            <a href="/mypage" class="button">マイページ</a>
            <a href="/sell" class="button">出品</a>
        </div>

    </header>

<main>
    @yield('content')
</main>
</b>
</html>