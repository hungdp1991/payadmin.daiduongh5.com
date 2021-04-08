<?php

namespace App\Http\Controllers\AdminAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class CompensationController extends AdminAPIBaseController
{
    /**
     * Refund
     * @return JsonResponse
     */
    public function refund()
    {
        // get api config
        $apiRefundUrl = Arr::get($this->apiConfig, 'API_REFUND_URL');
        $apiRefundKey = Arr::get($this->apiConfig, 'API_REFUND_KEY');

        // call api
        $client = new Client();
        try {
            $response = $client->get($apiRefundUrl, [
                'timeout' => 3.0,
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'scripts' => $this->encode(request()->compensationItem, $apiRefundKey)
                ]
            ]);
            $result = json_decode($response->getBody()->getContents(), true);

            // return error status
            if (!$result['status']) {
                return response()->json($result, 500);
            }

            return response()->json($result, 200);
        } catch (ClientException $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Generate refund param
     * @param $input
     * @param $key
     * @return string|void
     */
    private function encode($input, $key)
    {
        $algorithm = 'AES-256-CBC';
        $iv = substr($key, 0, 32);

        $key = hex2bin($key);
        $iv = hex2bin($iv);

        $output = openssl_encrypt($input, $algorithm, $key, OPENSSL_RAW_DATA, $iv);

        return bin2hex($output);
    }
}
