<?php

namespace Modules\User\Http\Requests;

use App\Enums\UserGender;
use App\Enums\UserStatus;
use App\Enums\UserTypes;
use App\Traits\HasApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    use HasApiRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->getRules([
            'first_name'      => 'nullable|string|max:200',
            'last_name'       => 'nullable|string|max:200',
            'national_code'   => 'nullable|string',
            'account_number'  => 'nullable|string',
            'birthday'        => 'nullable|date',
            'mobile'          => ['required', 'ir_mobile', Rule::unique('users', 'mobile')->ignore($this?->user?->id)->whereNull('deleted_at')],
            'email'           => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this?->user?->id)->whereNull('deleted_at')],
            'roles.*'         => 'nullable|exists:roles,id',
            'password'        => 'nullable|min:8|confirmed',
            'old_password'    => 'nullable|min:8',
            'sajam_code'      => 'nullable|string',
            'bank_name'       => 'nullable|string',
            'bank_number'     => 'nullable|string',
            'sheba_code'      => 'nullable|string',
            'type'            => ['nullable', Rule::in(array_column(UserTypes::cases(), 'name'))],
            'gender'          => ['nullable', Rule::in(array_column(UserGender::cases(), 'name'))],
        ]);

        if ( $this->user()?->can('users') ) {
            $data['status'] = ['nullable', 'bail', Rule::in(array_column(UserStatus::cases(), 'name'))];
        }

        return $data;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'files' => $this->documents ? array_filter($this->documents, fn($media) => is_object($media)) : [],
        ]);
    }

}
