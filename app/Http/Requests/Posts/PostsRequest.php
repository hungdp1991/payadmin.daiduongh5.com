<?php

namespace App\Http\Requests\Posts;

use App\Http\Requests\BaseRequest;

class PostsRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const POST_TITLE_MAX_LENGTH = 255;
    const POST_SLUG_MAX_LENGTH = 255;
    const POST_IMAGE_MAX_LENGTH = 255;
    const POST_INTRO_MAX_LENGTH = 255;

    /**
     * Error messages
     */
    const MESSAGES = [
        'categoryId' => [
            'required' => 'Không để trống',
            'exists' => 'Không tồn tại'
        ],
        'postTitle' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::POST_TITLE_MAX_LENGTH . ' kí tự'
        ],
        'postSlug' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::POST_SLUG_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'postImage' => [
            'max' => 'Nhập tối đa ' . self::POST_IMAGE_MAX_LENGTH . ' kí tự'
        ],
        'postIntro' => [
            'max' => 'Nhập tối đa ' . self::POST_INTRO_MAX_LENGTH . ' kí tự'
        ],
        'postContent' => [
            'required' => 'Không để trống',
        ]
    ];

    /**
     * Create role rule
     * @return array
     * @created 2020-02-17 LongTHK
     */
    protected function commonRules()
    {
        // define common rules
        $rules = [];

        // add validation item
        $rules['categoryId'] = [
            'bail',
            'required',
            'exists:category,id'
        ];
        $rules['postTitle'] = [
            'bail',
            'required',
            'max:' . self::POST_TITLE_MAX_LENGTH,
        ];
        $rules['postSlug'] = [
            'bail',
            'required',
            'max:' . self::POST_SLUG_MAX_LENGTH,
        ];
        $rules['postImage'] = [
            'bail',
            'max:' . self::POST_IMAGE_MAX_LENGTH,
        ];
        $rules['postIntro'] = [
            'bail',
            'max:' . self::POST_INTRO_MAX_LENGTH,
        ];
        $rules['postContent'] = [
            'bail',
            'required'
        ];


        // return common rule
        return $rules;
    }

    /**
     * Create messages
     * @return array
     * @created 2020-02-14 LongTHK
     */
    protected function commonMessages()
    {
        return $this->generateMessages(self::MESSAGES);
    }
}
