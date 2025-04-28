<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\SoldItem;

class PurchaseForm extends Component
{
    public $item;
    public $profile;
    public $shippingAddress;
    public $payment_method = '';

    protected $rules = [
        'payment_method' => 'required',
    ];

    public function submit()
    {
        $this->validate();

        $user = auth()->user();

        if (!$this->item) {
            session()->flash('error', '商品が見つかりません');
            return redirect()->route('mypage', ['tab' => 'buy']);
        }

        if (SoldItem::where('item_id', $this->item->id)->exists()) {
            session()->flash('error', 'この商品はすでに購入されています');
            return redirect()->route('mypage', ['tab' => 'buy']);
        }

        $shipping_address = session()->get('shipping_address', [
            'postal_code' => $this->profile->postal_code,
            'address' => $this->profile->address,
            'building' => $this->profile->building,
        ]);

        SoldItem::create([
            'buyer_id' => $user->id,
            'item_id' => $this->item->id,
            'shipping_address' => '〒' . $shipping_address['postal_code'] . ' ' . $shipping_address['address'] . ' ' . ($shipping_address['building'] ?? ''),
            'payment_method' => $this->payment_method,
        ]);

        return redirect()->route('mypage', ['tab' => 'buy']);
    }
    public function render()
    {
        return view('livewire.purchase-form');
    }
}
