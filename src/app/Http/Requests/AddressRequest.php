<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use function PHPUnit\Framework\returnArgument;

class AddressRequest extends FormRequest
{
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
            'name' => 'required',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの８文字の数字を入れてください',
            'address.required' => '住所を入力してください',
        ];
    }

}
