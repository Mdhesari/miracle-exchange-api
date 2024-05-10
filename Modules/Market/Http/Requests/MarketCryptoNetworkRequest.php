<?php

namespace Modules\Market\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarketCryptoNetworkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'crypto_network_ids'   => 'required|array',
            'crypto_network_ids.*' => 'required|exists:crypto_networks,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
