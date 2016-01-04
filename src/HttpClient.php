<?php

namespace QuestApi;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => getenv('QUESTIONNAIRE_URL') . '/api-client/',
            'defaults' => ['exceptions' => false],
        ]);
    }

    public function createPrivateAnswerSheet($questionnaire_id, $questionnaire_hash)
    {
        $response = $this->makeRequest('POST', 'create-private-answer-sheet', [
            'questionnaire_id'   => $questionnaire_id,
            'questionnaire_hash' => $questionnaire_hash,
        ], true, true);
        return $response;
    }

    public function sayHi()
    {
        return $this->makeRequest('GET', 'say-hi');
    }

    /**
     * @param string $method e.g: GET, POST
     * @param string $uri
     * @param array $query
     * @param bool $decode
     * @param bool $assoc
     * @return string | array
     * @throws \Exception
     */
    private function makeRequest($method, $uri, $query = [], $decode = true, $assoc = false)
    {
        /** @var ResponseInterface $response */
        $response = $this->client->request($method, $uri, [
            'query' => array_merge($query, [
                'username' => getenv('USERNAME'),
                'password' => getenv('PASSWORD'),
            ]),
        ]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        if ($decode) {
            return json_decode($response->getBody()->getContents(), $assoc);
        } else {
            return $response->getBody()->getContents();
        }
    }
}
