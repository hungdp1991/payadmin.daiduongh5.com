<?php

namespace App\Http\Requests\Categories;

use App\Http\Requests\BaseRequest;
use App\Models\Categories;

class CategoriesRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const CATEGORY_NAME_MAX_LENGTH = 255;
    const CATEGORY_SLUG_MAX_LENGTH = 255;
    private $rootCategoryId;

    /**
     * Error messages
     */
    const MESSAGES = [
        'categoryParentId' => [
            'in' => 'Không hợp lệ',
        ],
        'categoryName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::CATEGORY_NAME_MAX_LENGTH . ' kí tự',
        ],
        'categorySlug' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::CATEGORY_SLUG_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại',
            'regex' => 'Không hợp lệ'
        ],
    ];

    /**
     * Model instance
     */
    private $categoriesModel;

    /**
     * CategoriesRequest constructor.
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @created 2020-02-14
     */
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        // call parent constructor
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        // create model instance
        $this->categoriesModel = new Categories();

        // get root category id from config
        $this->rootCategoryId = config('constants.ROOT_CATEGORY_ID');
    }

    /**
     * Get parent categories ids list
     */
    private function getParentCategoriesList()
    {
        return $this->categoriesModel->getCategoriesListByLevel(1)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Create role rule
     * @return array
     * @created 2020-02-14 LongTHK
     */
    protected function commonRules()
    {
        // define common rules
        $rules = [];

        // add validation item
        $rules['categoryParentId'] = [
            'bail',
            'in:' . $this->rootCategoryId . ',' . implode(',', $this->getParentCategoriesList())
        ];
        $rules['categoryName'] = [
            'bail',
            'required',
            'max:' . self::CATEGORY_NAME_MAX_LENGTH
        ];
        $rules['categorySlug'] = [
            'bail',
            'required',
            'max:' . self::CATEGORY_SLUG_MAX_LENGTH,
            'regex:/^[A-Za-z0-9-_]+$/'
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
