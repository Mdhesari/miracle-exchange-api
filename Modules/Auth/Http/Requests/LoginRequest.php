<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => ['required', 'regex:/^[0-9]{11}$/'],
            'otp'    => ['required', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mobile' => substr($this->mobile, -10),
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
