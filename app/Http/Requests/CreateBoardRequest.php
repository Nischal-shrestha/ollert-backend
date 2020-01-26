<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Board\Board;

class CreateBoardRequest extends FormRequest
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
     * Get the error messages for all defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'name.required' => 'A Board name is required!',

            'description.string' => 'Invalid characters in board description!',

            'visibility.required' => 'Must set the board visibility to one of the values.',
            'visibility.in' => 'Invalid value for board visibility!',

            'background.required' => 'Must select on value for the board background!',
            'background.string' => 'Invalid characters in board background!'
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
            'name' => 'required',
            'description' => 'string|nullable',
            'visibility' => [
                'required',
                Rule::in(Board::VISIBILITY)
            ],
            'background' => 'required|string',
        ];
    }
}
