<?php

namespace Modules\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
			'bIdentif_code'	=>	'required|integer|unique:table_name,column_name,except,primary_key',
			'name'	=>	'nullable|string',
			'location'	=>	'nullable|string',
			'established_date'	=>	'nullable|date',
			'website'	=>	'nullable|string',
        ];
    }
}
