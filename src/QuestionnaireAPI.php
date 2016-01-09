<?php

namespace QuestApi;

class QuestionnaireAPI extends HttpClient
{
    public function createAnswerSheet($questionnaireId, $pushBackUrl = null)
    {
        return $this->makeRequest('post', 'create-answersheet', [
            'questionnaire_id' => $questionnaireId,
            'push_back_url'    => $pushBackUrl,
        ]);
    }

    public function sayHi()
    {
        return $this->makeRequest('get', 'say-hi');
    }
}
