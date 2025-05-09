<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Contracts\Service\Attribute\Required;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'required|integer|min:0',
            'description' => 'required|max:255',
            'condition' => 'required',
            'category_ids' =>'required',
            'image' => 'required|image|mimes:jpeg,png'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は数字で入力してください',
            'price.max' => '商品価格は0円以上で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'category_ids.required' => '商品のカテゴリーを選択してください',
            'image.required' => '商品画像のアップロードしてください',
            'image.image' => 'アップロードできる画像形式は.jpegまたは.png のみです',
            'image.mimes' => 'アップロードできる画像形式は.jpegまたは.pngのみです',
        ];
    }
}
