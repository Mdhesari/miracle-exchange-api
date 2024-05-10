<?php

namespace Modules\Notification\Http\Requests;

use App\Traits\HasApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
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
            'title'    => 'required|string|max:1000',
            'role'     => ['nullable', 'bail', 'string', Rule::in(['funder', 'investor'])],
            'message'  => 'nullable|string',
            'sends_at' => 'nullable|numeric',
            'channel'  => 'nullable|string',
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
