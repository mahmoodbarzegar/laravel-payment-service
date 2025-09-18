<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return ['gateway' => 'required', 'amount' => 'required'];
    }

    public function messages()
    {
        return [
            'gateway.required' => 'لطفا درگاه مورد نظر را انتخاب کنید!',
            'amount' => 'مبلغ پرداخت نامشخص است'
        ];
    }
}
