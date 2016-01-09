<?php

namespace QuestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;

class HttpClient
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_url' => getenv('QUESTIONNAIRE_URL') . '/api-client/',
            'defaults' => ['exceptions' => false],
        ]);
    }


    /**
     * @param string $method e.g: GET, POST
     * @param string $uri
     * @param array $query
     * @return array|string
     * @throws \Exception
     */
    protected function makeRequest($method, $uri, $query = [])
    {
        /** @var ResponseInterface $response */
        $response = $this->client->$method($uri, [
            'query' => array_merge($query, [
                'username' => getenv('QUESTIONNAIRE_USERNAME'),
                'password' => getenv('QUESTIONNAIRE_PASSWORD'),
            ]),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}