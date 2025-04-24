@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

    <div class="content">
        <div class="title">
            <h1>商品の出品</h1>
        </div>

        <div class="sell_content">
            <form action="/sell" method="post" enctype="multipart/form-data">
                @csrf
                <label class="item_image">商品画像</label>

                <div class="image_frame">
                    <output id="list" class="image_output">
                        @if (session('image_path'))
                                <img src="{{ asset(session('image_path')) }}" alt="商品画像" class="uploaded-image">
                                <input type="hidden" name="image" value="{{ session('image_path') }}">
                        @endif
                    </output>

                    <label class="custom-file-label">
                        画像を選択する
                        <input class="file" type="file" id="image" name="image" accept="image/*">
                    </label>
                </div>

                @error('image')
                    <p class="error-message">{{ $message }}</p>
                @enderror

                <h2 class="item_direction">商品の詳細</h2>

                <label class="category">カテゴリー</label>
                <div class="category-list">
                    @foreach ($categories as $category)
                        <div class="category-button {{ in_array($category->id, explode(',', old('category_id', ''))) ? 'active' : '' }}"  data-id="{{ $category->id }}">
                        {{ $category->name }}
                        </div>
                    @endforeach
                </div>

                <input type="hidden" name="category_ids" id="selected-categories" value="{{ old('category_ids') }}">

                @error('category_ids')
                    <p class="error-message">{{ $message }}</p>
                @enderror

                <label class="item_condition">商品の状態</label>
                    <select name="condition" id="condition">
                        <option value="">選択してください</option>
                        <option value="1" {{ old('condition') == 1 ? 'selected' : ''}}>良好</option>
                        <option value="2" {{ old('condition') == 2 ? 'selected' : ''}}>目立った傷や汚れなし</option>
                        <option value="3" {{ old('condition') == 3 ? 'selected' : ''}}>やや傷や汚れあり</option>
                        <option value="4" {{ old('condition') == 4 ? 'selected' : ''}}>状態が悪い</option>
                    </select>

                    @error('condition')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                <h2 class="direction_title">商品名と説明</h2>

                <label class="item_name">商品名</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}">

                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                <label class="brand">ブランド名</label>
                    <input type="text" name="brand" value="{{ old('brand') }}">

                <label class="item_description">商品の説明</label>
                    <textarea class="description_text" type="text" name="description"> {{ old('description') }}</textarea>

                    @error('description')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                <label class="item_price">販売価格</label>
                    <div class="price-container">
                    <span class="price_symbol">¥</span>
                    <input type="text" name="price" class="price" value="{{ old('price') }}">
                    </div>

                    @error('price')
                        <p class="error-message">{{ $message }}</p>
                    @enderror


                <button class= "exhibit_button" type="submit">出品する</button>

            </form>

        </div>


    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
            const imageInput = document.getElementById("image");
            const previewContainer = document.getElementById("list"); // プレビューを表示する要素

            imageInput.addEventListener("change", function (event) {
                previewContainer.innerHTML = ""; // 既存の画像をクリア

                const files = event.target.files;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const div = document.createElement("div");
                        div.className = "reader_file";
                        div.innerHTML = `<img class="reader_image" src="${e.target.result}" />`;
                        previewContainer.appendChild(div);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const categoryButtons = document.querySelectorAll(".category-button");
            const selectedCategoriesInput = document.getElementById("selected-categories");

            const selected = selectedCategoriesInput.value.split(",");
            categoryButtons.forEach(button => {
                if (selected.includes(button.dataset.id)) {
                    button.classList.add("active");
                }

                button.addEventListener("click", function () {
                    this.classList.toggle("active");

                    const selectedCategories = Array.from(categoryButtons)
                        .filter(btn => btn.classList.contains("active"))
                        .map(btn => btn.dataset.id);

                    selectedCategoriesInput.value = selectedCategories.join(",");
                });
            });
        });
    </script>

@endsection