<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'mobile'       => ['required', 'regex:/^[0-9]{10}$/'],
            'otp'          => ['required', 'string'],
            'inviter_code' => ['nullable', 'string', Rule::exists('users', 'invitation_code')->whereNot('invitation_code', $this->user?->invitation_code)],
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
