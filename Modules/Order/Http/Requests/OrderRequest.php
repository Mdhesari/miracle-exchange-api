<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'market_id'                 => 'required|exists:markets,id',
            'cumulative_quote_quantity' => 'required|numeric',
            'account_id'                => 'nullable|exists:accounts,id',
            'user_id'                   => 'nullable|exists:users,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ! $this->user_id || $this->user()->isOwner($this->user_id) || $this->user()->can('orders');
    }
}
