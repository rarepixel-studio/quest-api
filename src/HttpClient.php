<?php

namespace QuestApi;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use QuestApi\Exceptions\QuestException;

class HttpClient
{
    /**
     * @var Client
     */
    protected $client;

    private $username;

    private $password;

    public function __construct($baseUrl, $username, $password)
    {
        $this->client   = new Client([
            'base_uri'   => $baseUrl . '/api-client/',
            'exceptions' => false,
        ]);
        $this->username = $username;
        $this->password = $password;
    }


    /**
     * @param string $method e.g: get, post, delete
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
                'username' => $this->username,
                'password' => $this->password,
            ]),
        ]);

        $contents = $response->getBody()->getContents();

        if ($response->getStatusCode() != 200) {
            throw new QuestException($contents);
        }

        return json_decode($contents, true);
    }
}
