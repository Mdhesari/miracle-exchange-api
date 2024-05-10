<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user'               => 'nullable|exists:users,id',
            'quantity'           => 'required|numeric|gt:0',
            'currency'           => 'required|string',
            'crypto_wallet_hash' => 'required|string',
            'crypto_network_id'  => 'required|exists:networks,id',
        ];
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
}
