<?php

namespace App\Http\Requests\Users;

class UsersCreatingRequest extends UsersRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // get common rules
        $rules = parent::commonRules();

        // add rule
        $rules['username'][] = 'bail';
        $rules['username'][] = 'required';
        $rules['username'][] = 'min:' . parent::USER_NAME_MIN_LENGTH;
        $rules['username'][] = 'max:' . parent::USER_NAME_MAX_LENGTH;
        $rules['username'][] = 'unique:users,username';
        $rules['username'][] = 'regex:/^[a-z][0-9a-z]+$/i';

        $rules['password'][] = 'bail';
        $rules['password'][] = 'required';
        $rules['password'][] = 'min:' . parent::PASSWORD_MIN_LENGTH;
        $rules['password'][] = 'max:' . parent::PASSWORD_MAX_LENGTH;

        return $rules;
    }

    /**
     * Get validation messages
     * @return array
     */
    public function messages()
    {
        return parent::commonMessages();
    }
}
