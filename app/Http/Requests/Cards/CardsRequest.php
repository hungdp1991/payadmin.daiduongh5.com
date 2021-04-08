<?php

namespace App\Http\Requests\Card;

use App\Http\Requests\BaseRequest;

class CardsRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const CARD_TYPE_MAX_LENGTH = 10;
    const CARD_NAME_MAX_LENGTH = 100;

    /**
     * Error messages
     */
    const MESSAGES = [
        'cardType' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::CARD_TYPE_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'cardName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::CARD_NAME_MAX_LENGTH . ' kí tự',
        ]
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
        $rules['cardType'] = [
            'bail',
            'required',
            'max:' . self::CARD_TYPE_MAX_LENGTH
        ];
        $rules['cardName'] = [
            'bail',
            'required',
            'max:' . self::CARD_NAME_MAX_LENGTH
        ];

        // return common rule
        return $rules;
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
