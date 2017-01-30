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

        $input = $this->input();

        array_pull($input, '_token');
        array_pull($input, '_action');
        array_pull($input, 'storage');

        $keys = array_keys($input);
        foreach($keys as $key)
        {
            $rules[$key] = 'numeric|nullable';
        }
        //dd($rules);
        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $input = $this->input();

        array_pull($input, '_token');
        array_pull($input, '_action');
        array_pull($input, 'storage');

        $keys = array_keys($input);

        foreach($keys as $key)
        {
            $messages[$key] = 'Must be a number';
        }
        return $messages;
    }
}
