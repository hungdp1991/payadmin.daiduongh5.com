<?php

namespace App\Http\Requests\Games;

class GamesCreatingRequest extends GamesRequest
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

        // add more rules
        $rules['gameAgent'][] = 'unique:product,agent';
        $rules['gameSlug'][] = 'unique:product,slug';

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
