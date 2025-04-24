<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $user = Auth::user();
        $profile = $user->profile;

        $shipping_address = session()->get('shipping_address', [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        $this->merge([
            'shipping_address' => $shipping_address['postal_code'] . $shipping_address['address']
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => 'required',
            'shipping_address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払方法を選択してください',
            'shipping_address.required' => '配送先が入力されていません'
        ];
    }
}
