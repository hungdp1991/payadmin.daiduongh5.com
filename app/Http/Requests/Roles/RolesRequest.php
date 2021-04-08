<?php

namespace App\Http\Requests\Roles;

use App\Http\Requests\BaseRequest;

class RolesRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const ROLE_NAME_MAX_LENGTH = 20;

    /**
     * Error messages
     */
    const MESSAGES = [
        'roleName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::ROLE_NAME_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ]
    ];

    /**
     * Create role rule
     * @return array
     */
    protected function commonRules()
    {
        // define common rules
        $rules = [];

        // add validation item
        $rules['roleName'] = [
            'bail',
            'required',
            'max:' . self::ROLE_NAME_MAX_LENGTH
        ];

        // return common rule
        return $rules;
    }

    /**
     * Create messages
     * @return array
     */
    protected function commonMessages()
    {
        // get error messages
        $errorMessages = self::MESSAGES;

        // generate common messages
        $commonMessages = $this->generateMessages($errorMessages);

        // create error message
        return $commonMessages;
    }
}
