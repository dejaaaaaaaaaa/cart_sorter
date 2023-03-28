<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cart_id'    => 'required|integer',
            'product_id' => 'required|integer',
            'store_id'   => 'required|integer',
            'quantity'   => 'required|integer',
        ];
    }
}
