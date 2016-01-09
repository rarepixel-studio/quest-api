<?php

namespace QuestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;

class QuestionnaireAPI
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

    public function createAnswerSheet($questionnaireId)
    {
        return $this->makeRequest('post', 'create-answersheet', [
            'questionnaire_id' => $questionnaireId,
        ]);
    }

    public function getAnswersheetStatus($answersheetId)
    {
        return $this->makeRequest('get', 'answersheet-status', [
            'answersheet_id' => $answersheetId,
        ]);
    }

    public function sayHi()
    {
        return $this->makeRequest('get', 'say-hi');
    }

    /**
     * @param string $method e.g: GET, POST
     * @param string $uri
     * @param array $query
     * @return array|string
     * @throws \Exception
     */
    private function makeRequest($method, $uri, $query = [])
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
