<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MoveStorageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [];

        $rules['moveProducts.*'] = 'numeric|nullable|largerthenstock';

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages['moveProducts.*.numeric'] = 'Must be a number';

        return $messages;
    }
}
