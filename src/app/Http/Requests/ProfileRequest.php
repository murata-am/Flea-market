<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'image.mimes' => '画像には.jpg.ping形式のファイルを指定してください'
        ];
    }
}
