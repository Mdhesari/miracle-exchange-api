<?php

namespace Modules\Landing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LandingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'     => 'required|string',
            'market_id' => 'required|exists:markets,id',
            'slug'      => 'nullable|string',
            'meta'      => 'nullable|array',
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
