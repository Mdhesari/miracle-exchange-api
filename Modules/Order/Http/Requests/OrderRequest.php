<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Order\Enums\OrderStatus;

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
            'user_id'           => 'required|exists:users,id',
            'market_id'         => 'required|exists:markets,id',
            'executed_price'    => 'required|numeric',
            'executed_quantity' => 'required|numeric',
            'fill_percentage'   => 'required|numeric|between:0,100',
            'status'            => ['nullable', Rule::in(array_column(OrderStatus::cases(), 'name'))]
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
