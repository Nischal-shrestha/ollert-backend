<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user())
        {
            return false;
        }
        return true;
    }

    /**
 * Get the error messages for all defined validation rules.
 *
 * @return array
 */

    public function messages()
    {
        return [
            'name.required' => 'Your name is required!',
            'name.alpha' => 'You\'ve entered invalid name!',

            'email.required' => 'The email is invalid mate!',
            'email.unique' => 'The email is already taken! Try something else!',

            'password.required' => 'The password is invalid mate!',
            'password.confirmed' => 'The passwords do not match!'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6|confirmed',
        ];
    }
}
