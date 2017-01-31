<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StockStorageRequest extends FormRequest
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

        $input = $this->input('products');

        $keys = array_keys($input);

        for ($i = 0; $i < count($input); $i++) {

            $rules['products.' . $keys[$i]] = 'numeric|nullable';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $input = $this->input('products');

        $keys = array_keys($input);

        for ($i = 0; $i < count($input); $i++) {

            $messages['products.' . $keys[$i] . '.numeric'] = 'Must be a number';
        }
        return $messages;
    }
}
