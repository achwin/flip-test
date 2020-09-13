<?php

namespace App\Services\SlightlyBigFlip;

class Client
{
    protected $baseUri;

    public function __construct($base_uri = null)
    {
        $this->baseUri = $base_uri ?? env('API_DISBURSE_SERVICE');
    }

    public function createDisbursement($payload)
    {
        return $this->makeRequest("POST", "/disburse", ["json" => $payload]);
    }

    public function getDisbursementByTransactioID(int $transactionID)
    {
        return $this->makeRequest("GET", "/disburse/" . $transactionID);
    }

    private function makeRequest($method, $uri, $options = [])
    {
        $opt = [
            'base_uri' => $this->baseUri,
            'auth' => [
                env('API_BASIC_AUTH_USERNAME'),
                env('API_BASIC_AUTH_PASSWORD'),
            ],
            'timeout'  => env('API_TIMEOUT', 30),
        ];

        $client = new \GuzzleHttp\Client($opt);

        $response    = $client->request($method, $uri, $options);
        $responseStr = $response->getBody()->getContents();
        $responseObj = json_decode($responseStr, true);

        return $responseObj;
    }
}
