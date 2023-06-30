<?php

namespace Modules\Gateway\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Gateway\Traits\HasApiRequest;

class GatewayRequest extends FormRequest
{
    use HasApiRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRules([
            'title'          => 'required|string',
            'sheba_number'   => 'required|string',
            'account_number' => 'required|string',
            'account_name'   => 'required|string',
            'is_active'      => 'required|boolean',
        ]);
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
