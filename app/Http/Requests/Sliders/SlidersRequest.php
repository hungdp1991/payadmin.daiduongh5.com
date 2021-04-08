<?php

namespace App\Http\Requests\Sliders;

use App\Http\Requests\BaseRequest;

class SlidersRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const SLIDER_NAME_MIN_LENGTH = 1;
    const SLIDER_NAME_MAX_LENGTH = 100;
    const SLIDER_DESCRIPTION_MAX_LENGTH = 255;

    /**
     * Error messages
     */
    const MESSAGES = [
        'sliderName' => [
            'required' => 'Không để trống',
            'min' => 'Nhập ít nhất ' . self::SLIDER_NAME_MIN_LENGTH . ' kí tự',
            'max' => 'Nhập tối đa ' . self::SLIDER_NAME_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'sliderDescription' => [
            'max' => 'Nhập tối đa ' . self::SLIDER_DESCRIPTION_MAX_LENGTH . ' kí tự',
        ],
        'sliderItemsList' => [
            'required' => 'Không để trống'
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
        $rules['sliderName'] = [
            'bail',
            'required',
            'min:' . self::SLIDER_NAME_MIN_LENGTH,
            'max:' . self::SLIDER_NAME_MAX_LENGTH,
        ];
        $rules['sliderDescription'] = [
            'bail',
            'max:' . self::SLIDER_DESCRIPTION_MAX_LENGTH,
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
