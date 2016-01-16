<?php

namespace QuestApi;

class QuestionnaireAPI extends HttpClient
{
    public function createAnswerSheet($questionnaireId, $pushBackUrl = null)
    {
        $response = $this->makeRequest('post', 'create-answersheet', [
            'questionnaire_id' => $questionnaireId,
            'push_back_url'    => $pushBackUrl,
        ]);

        if (array_keys($response) == ['status', 'answersheet_id', 'link'] && $response['status'] == 'success') {
            return $response;
        } else {
            throw new \Exception('Invalid response for questionnaire[' . $questionnaireId . ']');
        }
    }

    public function sayHi()
    {
        return $this->makeRequest('get', 'say-hi');
    }
}
