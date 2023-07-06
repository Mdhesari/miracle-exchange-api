<?php

namespace Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'          => 'required|string',
            'sheba_number'   => 'required|string|ir_sheba',
            'account_number' => 'required|string',
            'account_name'   => 'required|string',
            'user_id'        => 'nullable|exists:users,id',
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
