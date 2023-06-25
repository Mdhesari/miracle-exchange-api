<?php

namespace Modules\Auth\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class SMSIR
{

    protected string $APIKey;

    protected string $SecretKey;

    protected string $base;

    /**
     * SMSIR constructor.
     */

    public function __construct()
    {
        $this->APIKey = config('otplogin.smsir-api-key');

        $this->SecretKey = config('otplogin.smsir-secret-key');

        $this->base = 'https://RestfulSms.com/api/';
    }

    /**
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */

    public function getCredit(): int
    {
        $request = $this->client(true)->get('https://RestfulSms.com/api/credit');

        if ($request->getStatusCode() != 201) {

            throw new \Exception('Failed to get data from SMS.ir Api', $request->getStatusCode());

        }

        $request = json_decode($request->getBody()->getContents());

        if (!$request->IsSuccessful) {

            throw new \Exception('Connection to SMS.ir Api was unsuccessful');

        }

        return $request->Credit;
    }

    /**
     * @param bool $isAuthenticated
     * @return Client
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    private function client(bool $isAuthenticated = false): Client
    {
        $headers = [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ];

        if ($isAuthenticated) {

            $headers['x-sms-ir-secure-token'] = $this->generateToken();

        }

        return new Client([
            'headers' => $headers,
            'http_error' => false
        ]);
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */

    private function generateToken(): string
    {
        $request = $this->client()->post($this->base . 'Token', [
            'json' => [
                'UserApiKey' => $this->APIKey,
                'SecretKey' => $this->SecretKey,
            ],
        ]);

        if ($request->getStatusCode() != 201) {

            throw new \Exception($request->getBody()->getContents(), $request->getStatusCode());

        }

        $request = json_decode($request->getBody()->getContents());

        if (!$request->IsSuccessful) {

            throw new \Exception('Connection to SMS.ir Api was unsuccessful');

        }

        return $request->TokenKey;
    }

    /**
     * @param string $from
     * @param string $until
     * @param string $rows
     * @param string $pages
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */

    public function sentMessagesLogs(string $from, string $until, string $rows, string $pages): Collection
    {
        $request = $this->client(true)->get('https://RestfulSms.com/api/MessageSend', [
            'query' => [
                'Shamsi_FromDate' => $from,
                'Shamsi_ToDate' => $until,
                'RowsPerPage' => $rows,
                'RequestedPageNumber' => $pages
            ],
        ]);

        if ($request->getStatusCode() != 201) {

            throw new \Exception('Failed to get data from SMS.ir Api', $request->getStatusCode());

        }

        $request = json_decode($request->getBody()->getContents());

        if (!$request->IsSuccessful) {

            throw new \Exception('Connection to SMS.ir Api was unsuccessful');

        }

        return collect($request->Messages);
    }

    /**
     * @param array $data
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */

    public function UltraFastSend(array $data): void
    {
        $request = $this->client(true)->post('https://RestfulSms.com/api/UltraFastSend', [
            'json' => $data,
        ]);

        if ($request->getStatusCode() != 201) {

            throw new \Exception('Failed to get data from SMS.ir Api', $request->getStatusCode());

        }

        $request = json_decode($request->getBody()->getContents());

        if (!$request->IsSuccessful) {

            throw new \Exception('Connection to SMS.ir Api was unsuccessful');

        }

    }
}
