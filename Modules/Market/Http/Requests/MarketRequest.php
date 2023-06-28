<?php

namespace Modules\Market\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Market\Traits\HasApiRequest;

class MarketRequest extends FormRequest
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
            'name'   => 'nullable|string',
            'symbol' => 'required|string',
            'price'  => 'required|numeric',
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
