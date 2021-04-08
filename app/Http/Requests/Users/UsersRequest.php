<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class UsersRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const USER_NAME_MIN_LENGTH = 2;
    const USER_NAME_MAX_LENGTH = 20;
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 20;
    const FULL_NAME_MAX_LENGTH = 100;

    /**
     * Error messages
     */
    const MESSAGES = [
        'fullName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::FULL_NAME_MAX_LENGTH . ' kí tự',
        ],
        'username' => [
            'required' => 'Không để trống',
            'min' => 'Nhập tối thiếu ' . self::USER_NAME_MIN_LENGTH . ' kí tự',
            'max' => 'Nhập tối đa ' . self::USER_NAME_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại',
            'regex' => 'Không hợp lệ'
        ],
        'password' => [
            'required' => 'Không để trống',
            'min' => 'Nhập tối thiếu ' . self::PASSWORD_MIN_LENGTH . ' kí tự',
            'max' => 'Nhập tối đa ' . self::PASSWORD_MAX_LENGTH . ' kí tự'
        ],
        'roleId' => [
            'required' => 'Không để trống',
            'exists' => 'Không hợp lệ'
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
        $rules['fullName'] = [
            'bail',
            'required',
            'max:' . self::FULL_NAME_MAX_LENGTH
        ];
        $rules['roleId'] = [
            'bail',
            'required',
            'exists:roles,id'
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
        return $this->generateMessages(self::MESSAGES);
    }
}
