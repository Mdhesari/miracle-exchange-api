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
            'market_id'          => 'required|exists:markets,id',
            'crypto_wallet_hash' => 'nullable|required_if:user,null|string',
            'crypto_network_id'  => 'nullable|required_if:user,null|exists:crypto_networks,id',
            'account_id'         => 'nullable|exists:accounts,id',
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
