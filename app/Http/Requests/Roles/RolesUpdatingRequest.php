<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RolesUpdatingRequest extends RolesRequest
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
        $rules['roleName'][] = 'unique:roles,name,' . request()->roleId;

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
