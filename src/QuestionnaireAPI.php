<?php

namespace QuestApi;

use QuestApi\Exceptions\QuestException;

class QuestionnaireAPI extends HttpClient
{
    public function createAnswersheet($questionnaireId, $pushBackUrl = null)
    {
        $response = $this->makeRequest('post', 'create-answersheet', [
            'questionnaire_id' => $questionnaireId,
            'push_back_url'    => $pushBackUrl,
        ]);

        if (array_key_exists('status', $response) && array_key_exists('answersheet_id', $response) && array_key_exists('link', $response) && $response['status'] == 'success') {
            return $response;
        } else {
            throw new QuestException('Invalid response for questionnaire[' . $questionnaireId . ']');
        }
    }

    public function sayHi()
    {
        return $this->makeRequest('get', 'say-hi');
    }
}
