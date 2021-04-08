<?php

namespace App\Http\Controllers\AdminAPI;

use Illuminate\Support\Arr;

class ConfigurationsController extends AdminAPIBaseController
{
    /**
     * ConfiguarationController constructor.
     * @created 2020-02-18 LongTHK
     */
    public function __construct()
    {
        // call parent constructor
        parent::__construct();
    }

    /**
     * Get payment types list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-18 LongTHK
     */
    public function getPaymentTypesList()
    {
        return response()->json(Arr::get($this->constantsConfig, 'PAYMENT_TYPE_LIST'), 200);
    }

    /**
     * Get server status list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-18 LongTHK
     */
    public function getServerStatusList()
    {
        return response()->json(Arr::get($this->constantsConfig, 'SERVER_STATUS_LIST'), 200);
    }

    /**
     * Get server status list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-26 LongTHK
     */
    public function getTransactionStatusList()
    {
        return response()->json(Arr::get($this->constantsConfig, 'TRANSACTION_STATUS_LIST'), 200);
    }

    /**
     * Get gold types list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-24 LongTHK
     */
    public function getGoldTypesList()
    {
        return response()->json(Arr::get($this->constantsConfig, 'GOLD_TYPES_LIST'), 200);
    }

    /**
     * Get server status list
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-26 LongTHK
     */
    public function getOSList()
    {
        return response()->json(Arr::get($this->constantsConfig, 'OS_LIST'), 200);
    }
}
