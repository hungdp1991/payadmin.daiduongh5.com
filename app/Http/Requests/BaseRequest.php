<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Generate messages that will apply when validation fail
     * @param $arrayErrorMessages
     * @return array
     */
    protected function generateMessages($arrayErrorMessages)
    {
        // define messages storage
        $messages = [];

        // loop $arrayErrorMessages to generate commom messages
        foreach ($arrayErrorMessages as $validationItem => $validationInfo) {
            foreach ($validationInfo as $validationKey => $validationMessage) {
                // generate validation key
                $validationKey = $validationItem . '.' . $validationKey;
                // create error message array
                $messages[$validationKey] = Arr::get($arrayErrorMessages, $validationKey);
            }
        }

        return $messages;
    }

    /**
     * Response when validation fail
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
