<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
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
        $data = [
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'users'         => 'nullable|array',
            'users.*'       => 'exists:users,id',
        ];

        if ( strtolower($this->method()) === 'post' ) {
            $data['name'] = 'required|string|unique:roles,name';
        }

        return $data;
    }
}
