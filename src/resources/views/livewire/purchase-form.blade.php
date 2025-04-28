<form wire:submit.prevent="submit" class="all_content">
    <div class="left_content">
        <div class="item_content">
            <img class="item_image" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
            <div class="item_details">
                <h2 class="item_name">{{ $item->name }}</h2>

                <div class="item_price">
                    <span class="price_icon">￥</span>
                    <span class="price_value">{{ number_format($item->price) }}</span>
                </div>
            </div>
        </div>

        <hr class="section-divider">

        <div class="purchase_form">
            <div class="payment_block">
                <p class="payment_title">支払い方法</p>
                <select class="payment" wire:model="payment_method" id="payment_method">
                    <option value="">選択してください</option>
                    <option value="コンビニ支払い">コンビニ支払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>
            </div>

            @error('payment_method')
                <p class="error-message">{{ $message }}</p>
            @enderror

            <hr class="section-divider">

            <div class="shipping_block">
                <div class="shipping_title">
                    <div class="shipping_title_header">配送先</div>
                    <a class="address_edit" href="{{ route('address.edit', ['item_id' => $item->id]) }}">変更する</a>
                </div>
                <div class="shipping_address">
                    <p class="postal_code">〒 {{ $shippingAddress['postal_code'] ?? $profile->postal_code }}</p>
                    <p class="address">{{ $shippingAddress['address'] ?? $profile->address }}
                        {{ $shippingAddress['building'] ?? $profile->building }}</p>
                </div>
            </div>
        </div>

        <hr class="section-divider">
    </div>

    <div class="right_content">
        <div class="item_table">
            <table>
                <tr>
                    <th>商品代金</th>
                    <th>
                        <span class="price_icon">￥</span>
                        <span class="price_value">{{ number_format($item->price) }}</span>
                    </th>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <th class="payment">
                        {{ $payment_method ?: '選択してください' }}
                    </th>
                </tr>
            </table>
        </div>
        <button type="submit" class="purchase_button">購入する</button>
    </div>
</form>

