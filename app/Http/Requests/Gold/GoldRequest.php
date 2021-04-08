<?php

namespace App\Http\Requests\Gold;

use App\Http\Requests\BaseRequest;

class GoldRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const GOLD_NAME_MAX_LENGTH = 255;
    const GOLD_DESCRIPTION_MAX_LENGTH = 255;
    const GOLD_IMAGE_MAX_LENGTH = 255;
    const GOLD_AMOUNT_MIN_VALUE = 0;
    const GOLD_AMOUNT_MAX_VALUE = 2147483647;
    const GOLD_MIN_VALUE = 0;
    const GOLD_MAX_VALUE = 2147483647;


    /**
     * Error messages
     */
    const MESSAGES = [
        'goldName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::GOLD_NAME_MAX_LENGTH . ' kí tự',
        ],
        'goldDescription' => [
            'max' => 'Nhập tối đa ' . self::GOLD_DESCRIPTION_MAX_LENGTH . ' kí tự',
        ],
        'goldImage' => [
            'max' => 'Nhập tối đa ' . self::GOLD_IMAGE_MAX_LENGTH . ' kí tự',
        ],
        'goldType' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::GOLD_IMAGE_MAX_LENGTH . ' kí tự',
        ],
        'gameId' => [
            'required' => 'Không để trống',
            'exists' => 'Không tồn tại',
        ],
        'goldAmount' => [
            'numeric' => 'Nhập giá trị số',
            'min' => 'Nhập tối thiểu ' . self::GOLD_AMOUNT_MIN_VALUE,
            'max' => 'Nhập tối đa ' . self::GOLD_AMOUNT_MAX_VALUE,
        ],
        'goldValue' => [
            'numeric' => 'Nhập giá trị số',
            'min' => 'Nhập tối thiểu ' . self::GOLD_MIN_VALUE,
            'max' => 'Nhập tối đa ' . self::GOLD_MAX_VALUE,
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
        $rules['goldName'] = [
            'bail',
            'required',
            'max:' . self::GOLD_NAME_MAX_LENGTH
        ];
        $rules['goldDescription'] = [
            'bail',
            'max:' . self::GOLD_DESCRIPTION_MAX_LENGTH
        ];
        $rules['goldImage'] = [
            'bail',
            'max:' . self::GOLD_IMAGE_MAX_LENGTH
        ];
        $rules['goldType'] = [
            'bail',
            'required',
            'in:' . implode(',', $this->getGoldTypeValuesList())
        ];
        $rules['gameId'] = [
            'bail',
            'required',
            'exists:product,id'
        ];
        $rules['goldAmount'] = [
            'bail',
            'nullable',
            'numeric',
            'min:' . self::GOLD_AMOUNT_MIN_VALUE,
            'max:' . self::GOLD_AMOUNT_MAX_VALUE
        ];
        $rules['goldValue'] = [
            'bail',
            'nullable',
            'numeric',
            'min:' . self::GOLD_MIN_VALUE,
            'max:' . self::GOLD_MAX_VALUE
        ];

        // return common rule
        return $rules;
    }

    /**
     * Get gold types values list
     * @return array
     * @created 2020-02-24 LongTHK
     */
    private function getGoldTypeValuesList()
    {
        // get gold types list
        $goldTypesList = config('constants.GOLD_TYPES_LIST');


        // generate gold type values list
        $goldTypeValuesList = [];
        foreach ($goldTypesList as $goldTypeItem) {
            $goldTypeValuesList[] = $goldTypeItem['value'];
        }

        return $goldTypeValuesList;
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
