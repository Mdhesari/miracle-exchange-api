<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user'              => 'nullable|exists:users,id',
            'quantity'          => 'required|numeric|gt:0',
            'market_id'         => 'required|exists:markets,id',
            'crypto_network_id' => 'required|exists:crypto_networks,id',
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
