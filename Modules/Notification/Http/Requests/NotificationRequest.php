<?php

namespace Modules\Notification\Http\Requests;

use App\Traits\HasApiRequest;
use Illuminate\Foundation\Http\FormRequest;

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
            'message'  => 'nullable|string',
            'sends_at' => 'nullable|numeric',
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
