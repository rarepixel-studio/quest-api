<?php

namespace QuestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
        try {
            /** @var ResponseInterface $response */
            $response = $this->client->$method($uri, [
                'query' => array_merge($query, [
                    'username' => $this->username,
                    'password' => $this->password,
                ]),
            ]);
        } catch (RequestException $e) {
            throw new QuestException(get_class($e) . ' thrown while trying to send a request to questionnaire server', $e->getCode(), $e);
        }

        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }
}
