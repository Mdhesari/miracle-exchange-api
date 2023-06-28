<?php

namespace Modules\Market\Services\Navasan;

use GuzzleHttp\Client;

class Navasan
{
    private Client $client;

    private string $baseUrl;

    public function __construct(
        private string $apiKey
    )
    {
        $this->baseUrl = 'https://api.navasan.tech';
        $this->client = new Client([
            'base_uri'    => $this->baseUrl,
            'headers'     => [
                'accept' => 'application/json',
            ],
            'http_errors' => false
        ]);
    }

    public function getLatest()
    {
        $response = $this->client->post('latest', [
            'query' => [
                'api_key' => $this->apiKey,
            ],
        ]);

        return $this->response($response);
    }

    private function response(\Psr\Http\Message\ResponseInterface $data)
    {
        return json_decode($data->getBody()->getContents(), true);
    }
}
