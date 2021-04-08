<?php

namespace App\Http\Requests\Servers;

use App\Http\Requests\BaseRequest;

class ServersRequest extends BaseRequest
{
    /**
     * Rule values
     */
    const SERVER_ID_MAX_LENGTH = 10;
    const SERVER_NAME_MAX_LENGTH = 50;
    const SERVER_SLUG_MAX_LENGTH = 50;
    const KEY_WEB_CHARGE_MAX_LENGTH = 100;
    const KEY_IAP_CHARGE_MAX_LENGTH = 100;

    /**
     * Error messages
     */
    const MESSAGES = [
        'gameId' => [
            'required' => 'Không để trống',
            'exists' => 'Không tồn tại',
        ],
        'serverId' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::SERVER_ID_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'serverName' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::SERVER_NAME_MAX_LENGTH . ' kí tự',
        ],
        'serverSlug' => [
            'required' => 'Không để trống',
            'max' => 'Nhập tối đa ' . self::SERVER_SLUG_MAX_LENGTH . ' kí tự',
            'unique' => 'Đã tồn tại'
        ],
        'serverStatus' => [
            'required' => 'Không để trống',
            'in' => 'Không hợp lệ'
        ],
        'paymentStatus' => [
            'required' => 'Không để trống'
        ],
        'keyWebCharge' => [
            'max' => 'Nhập tối đa ' . self::KEY_WEB_CHARGE_MAX_LENGTH . ' kí tự',
        ],
        'keyIAPCharge' => [
            'max' => 'Nhập tối đa ' . self::KEY_IAP_CHARGE_MAX_LENGTH . ' kí tự',
        ],
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
        $rules['gameId'] = [
            'bail',
            'required',
            'exists:product,id'
        ];
        $rules['serverId'] = [
            'bail',
            'required',
            'max:' . self::SERVER_ID_MAX_LENGTH
        ];
        $rules['serverName'] = [
            'bail',
            'required',
            'max:' . self::SERVER_NAME_MAX_LENGTH,
        ];
        $rules['serverSlug'] = [
            'bail',
            'required',
            'max:' . self::SERVER_SLUG_MAX_LENGTH,
        ];
        $rules['serverStatus'] = [
            'bail',
            'required',
            'in:' . implode(',', $this->getServerStatusList())
        ];
        $rules['paymentStatus'] = [
            'bail',
            'required'
        ];
        $rules['keyWebCharge'] = [
            'bail',
            'max:' . self::KEY_WEB_CHARGE_MAX_LENGTH
        ];
        $rules['keyIAPCharge'] = [
            'bail',
            'max:' . self::KEY_IAP_CHARGE_MAX_LENGTH
        ];

        // return common rule
        return $rules;
    }

    /**
     * Get server status list
     * @return array
     * @created 2020-02-20 LongTHK
     */
    private function getServerStatusList()
    {
        // get server status list
        $serverStatusList = config('constants.SERVER_STATUS_LIST');


        // generate server status list
        $serverStatusValuesList = [];
        foreach ($serverStatusList as $serverStatusItem) {
            $serverStatusValuesList[] = $serverStatusItem['value'];
        }

        return $serverStatusValuesList;
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
