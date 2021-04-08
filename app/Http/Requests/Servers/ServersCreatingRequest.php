<?php

namespace App\Http\Requests\Servers;


class ServersCreatingRequest extends ServersRequest
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
        $rules['serverId'][] = 'unique:servers,server_id,' . $this->id . ',id,product_id,' . $this->gameId;
        $rules['serverSlug'][] = 'unique:servers,server_slug';

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
