@extends('layouts.app')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
    <div class="all_content">
        <div class="left_content">
            <div class="image-container">
                <img class="item_image" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @if($soldItem)
                    <span class="sold-label">
                        <span class="sold-text">Sold</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="right_content">
            <class="item_content">
                <h1 class="item_name">{{ $item->name }}</h1>
                <p class="brand_name">{{ $item->brand_name }}</p>
                <p class="item_price">
                    <span class="price_icon">￥</span>
                    <span class="price_value">{{ $item->price }}</span>
                    <span class="price_tax">(税込)</span>
                </p>

                <div class="like_comment_button">
                <div class="item">
                    <button class="like-btn" data-item-id="{{ $item->id }}">
                        @if($item->isLikedBy(Auth::user()))
                            ⭐
                        @else
                            ☆
                        @endif
                    </button>
                    <p class="like-count"> <span id="like-count-{{ $item->id }}">{{ $item->like_count }}</span></p>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function () {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $(".like-btn").click(function () {
                                var button = $(this);
                                var itemId = button.data("item-id");

                                $.ajax({
                                    url: "/like/" + itemId,
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function (response) {
                                        if (response.status === "liked") {
                                            button.text("⭐");
                                        } else {
                                            button.text("☆");
                                        }
                                        $("#like-count-" + itemId).text(response.like_count);
                                    },
                                    error: function () {
                                        alert("エラーが発生しました");
                                    }
                                });
                            });
                        });
                    </script>
                </div>

                    <div class="comment_icon">💬</div>
                        <p class="comment_count">{{ $item->comments ? $item->comments->count() : 0 }}</p>
                </div>

                <a class="purchase" href="{{ route('purchase.index', ['item_id' => $item->id]) }}" @if($soldItem) style="pointer-events: none; background-color: gray;" @endif>
                    @if($soldItem)
                        売り切れです
                    @else
                        購入手続きへ
                    @endif
                </a>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $(".purchase").click(function (event) {
                            event.preventDefault();

                            var url = $(this).attr("href");

                            window.location.href = url;
                        });
                    });
                </script>

                <section class="item_description">
                    <h3>商品説明</h3>
                    <p class="description_content">{{ $item->description }}</p>
                </section>

                <h3>商品の情報</h3>
                <section>
                    <div class="item_info">
                        <table>
                            <tr>
                                <td class="category_title">カテゴリー</td>
                                <td class="category">
                                    @if (!empty($categories) && count($categories) > 0)
                                        @foreach ($item->categories as $category)
                                            <span class="category_tag">{{ $category->name }}</span>
                                        @endforeach
                                    @else
                                        <div class="category_tag">カテゴリーなし</div>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td class="item_condition">商品の状態</td>
                                <td class="status">
                                    @if ($item->condition == 1)
                                        良好
                                    @elseif ($item->condition == 2)
                                        目立った傷や汚れなし
                                    @elseif ($item->condition == 3)
                                        やや傷や汚れあり
                                    @elseif ($item->condition == 4)
                                        状態が悪い
                                    @else
                                        不明
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </section>

                    <h3 class="comment_label">コメント ({{ $item->comments ? $item->comments->count() : 0 }})</h3>
                    <p class="comment_user">

                    <div id="comments">
                    @if($item->comments->count() > 0)
                            @foreach($item->comments as $comment)
                                <div class="comment">
                                    <img src="{{ asset($comment->user->profile->image && $comment->user->profile->image ? 'storage/' . $comment->user->profile->image : 'default-profile.png') }}" alt="" class="profile-img">
                                    <strong class="comment-user">{{ $comment->user->name }}</strong>
                                    <p class="comment-text"> {{ $comment->content }}</p>
                                </div>
                            @endforeach

                    @else
                        <p id="no-comments">まだコメントはありません。</p>
                    @endif
                    </div>
                    </p>

                    <form action="{{ route('comment.store', ['item_id' => $item->id]) }}" method="post">
                        @csrf
                        <label for="text" class="item_comment">商品へのコメント</label>
                        <textarea id="comment-content" type="text" name="content" class="comment">{{ old('content') }}</textarea>

                        <div id="comment-error" class="error-message"></div>

                        <button class="comment_button" type="button" id="submit-comment" data-item-id="{{ $item->id }}">コメントを送信する</button>

                    </form>

                    <script>
                        $(document).ready(function () {
                            $("#submit-comment").click(function (event) {
                                event.preventDefault();

                                $.ajaxSetup({
                                    headers: {
                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                    }
                                });

                                let itemId = $(this).data("item-id");
                                let content = $("#comment-content").val().trim();
                                let errorDiv = $("#comment-error");

                                errorDiv.text("");

                                $.ajax({
                                    url: `/item/${itemId}/comment`,
                                    type: "POST",
                                    data: { content: content },
                                    success: function (response) {
                                        let comment = response.comment;

                                        errorDiv.text("");

                                        $("#no-comments").remove();
                                        $("#comments").append(`
                                            <div class="comment">
                                                <div class="comment-header">
                                                    <img src="${comment.user.profile_image ?? 'default-profile.png'}" alt="" class="profile-img">
                                                    <p class="comment-user">${comment.user.name}</p>
                                                </div>
                                                <p class="comment-text">${comment.content}</p>
                                            </div>
                                        `);
                                    $("#comment-content").val("");

                                    let currentCount = parseInt($(".comment_count").text());
                                        $(".comment_count").text(currentCount + 1);
                                        $(".comment_label").text(`コメント (${currentCount + 1})`);

                                    setTimeout(function () {
                                            console.log("コメントが反映されました");
                                        }, 100);
                                    },

                                    error: function (xhr) {
                                        if (xhr.status === 422) {
                                            let errors = xhr.responseJSON.errors;
                                            $("#comment-error").text(errors.content[0]);
                                        } else {
                                            alert("コメント投稿に失敗しました");
                                        }
                                    }
                                });
                            });
                            });
                    </script>

            </div>
        </div>

    </div>



@endsection