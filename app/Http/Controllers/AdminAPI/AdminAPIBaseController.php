<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAPIBaseController extends Controller
{
    /**
     * Config properties
     */
    protected $constantsConfig;
    protected $apiConfig;

    /**
     * AdminAPIBaseController constructor.
     */
    public function __construct()
    {
        // get config
        $this->constantsConfig = config('constants');
        $this->apiConfig = config('api');
    }
}
