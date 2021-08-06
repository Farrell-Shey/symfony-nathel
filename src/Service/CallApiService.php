<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getOsuData():array
    {
        $response = $this->client->request(
            'GET',
            'https://osu.ppy.sh/oauth/authorize'
        );
        return $response->toArray();
    }
}