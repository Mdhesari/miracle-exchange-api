<?php

namespace Modules\Helpdesk\Http\Requests;

use Modules\Helpdesk\Entities\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ( is_null($this->ticket) ) return true;

        return Gate::check('message', $this->ticket);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject'    => 'required|string',
            'department' => 'required|string',
            'notes'      => 'nullable|string',
            'user_id'    => 'nullable|exists:users,id'
        ];
    }
}
