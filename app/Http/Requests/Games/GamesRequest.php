<?php

namespace App\Http\Requests\Games;

use App\Http\Requests\BaseRequest;

class GamesRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const GAME_AGENT_MAX_LENGTH = 10;
    const GAME_NAME_MAX_LENGTH = 100;
    const GAME_SLUG_MAX_LENGTH = 100;
    const GAME_IMAGE_MAX_LENGTH = 255;
    const GAME_REDIRECT_MAX_LENGTH = 255;

    /**
     * Error messages
     */
    const MESSAGES = [
        'gameAgent' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::GAME_AGENT_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại',
            'regex' => 'Không hợp lệ'
        ],
        'gameName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::GAME_NAME_MAX_LENGTH . ' kí tự',
        ],
        'gameSlug' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::GAME_SLUG_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'gameImage' => [
            'max' => 'Nhập tối đa ' . self::GAME_IMAGE_MAX_LENGTH . ' kí tự',
        ],
        'paymentType' => [
            'required' => 'Không để trống',
            'in' => 'Không hợp lệ'
        ],
        'gameRedirect' => [
            'max' => 'Nhập tối đa ' . self::GAME_REDIRECT_MAX_LENGTH . ' kí tự',
            'required_if' => 'Không để trống'
        ],
    ];

    /**
     * Create role rule
     * @return array
     * @created 2020-02-19 LongTHK
     */
    protected function commonRules()
    {
        // define common rules
        $rules = [];

        // add validation item
        $rules['gameAgent'] = [
            'bail',
            'required',
            'max:' . self::GAME_AGENT_MAX_LENGTH,
            'regex:/^[a-z][0-9a-z]+$/i'
        ];
        $rules['gameName'] = [
            'bail',
            'required',
            'max:' . self::GAME_NAME_MAX_LENGTH
        ];
        $rules['gameSlug'] = [
            'bail',
            'required',
            'max:' . self::GAME_SLUG_MAX_LENGTH
        ];
        $rules['gameImage'] = [
            'bail',
            'max:' . self::GAME_IMAGE_MAX_LENGTH
        ];
        $rules['paymentType'] = [
            'bail',
            'required',
            'in:' . implode(',', $this->getPaymentTypeValuesList())
        ];
        $rules['gameRedirect'] = [
            'bail',
            'max:' . self::GAME_REDIRECT_MAX_LENGTH,
            'required_if:paymentType,outer_link'
        ];

        // return common rule
        return $rules;
    }

    /**
     * Get payment types values list
     * @return array
     * @created 2020-02-19 LongTHK
     */
    private function getPaymentTypeValuesList()
    {
        // get payment types list
        $paymentTypesList = config('constants.PAYMENT_TYPE_LIST');


        // generate payment type values list
        $paymentTypeValuesList = [];
        foreach ($paymentTypesList as $paymentTypeItem) {
            $paymentTypeValuesList[] = $paymentTypeItem['value'];
        }

        return $paymentTypeValuesList;
    }

    /**
     * Create messages
     * @return array
     * @created 2020-02-19 LongTHK
     */
    protected function commonMessages()
    {
        return $this->generateMessages(self::MESSAGES);
    }
}
