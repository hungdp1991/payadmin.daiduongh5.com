<?php

namespace App\Http\Requests;

use App\Rules\OldPassword;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    const NEW_PASSWORD_MIN_LENGTH = 6;

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
        return [
            'oldPassword' => [
                'required',
                new OldPassword()
            ],
            'newPassword' => [
                'required',
                'min:' . self::NEW_PASSWORD_MIN_LENGTH
            ],
            'confirmedPassword' => [
                'required',
                'same:newPassword'
            ]
        ];
    }

    /**
     * Messages that will be apply to request
     * @return array
     */
    public function messages()
    {
        return [
            'oldPassword.required' => 'Không để trống',

            'newPassword.required' => 'Không để trống',
            'newPassword.min' => 'Ít nhất ' . self::NEW_PASSWORD_MIN_LENGTH . ' kí tự',

            'confirmedPassword.required' => 'Không để trống',
            'confirmedPassword.same' => 'Mật khẩu không khớp'
        ];
    }

    /**
     * Response when validation fail
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
