<?php

namespace QuestApiTest\Integration;

use PHPUnit_Framework_TestCase;
use QuestApi\Exceptions\QuestException;
use QuestApi\QuestionnaireAPI;

class QuestionnaireAPITest extends PHPUnit_Framework_TestCase
{
    /**
     * @var QuestionnaireAPI
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new QuestionnaireAPI(getenv('QUESTIONNAIRE_URL'), getenv('QUESTIONNAIRE_USERNAME'), getenv('QUESTIONNAIRE_PASSWORD'));
    }

    public function test_say_hi()
    {
        $response = $this->client->sayHi();
        $this->assertEquals('hi', $response);
    }

    public function test_create_answersheet_without_enough_arguments()
    {
        $exceptionThrown = false;
        try {
            $this->client->createAnswersheet(null);
        } catch (QuestException $e) {
            $exceptionThrown = true;
            $this->assertEquals(QuestException::class, get_class($e));
            $this->assertTrue(array_key_exists('errors', json_decode($e->getMessage())));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_create_answersheet_for_not_invalid_questionnaire()
    {
        $exceptionThrown = false;
        try {
            $this->client->createAnswersheet(1111111111);
        } catch (QuestException $e) {
            $exceptionThrown = true;
            $this->assertEquals(['status' => 'questionnaire[1111111111] not found'], json_decode($e->getMessage(), true));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_create_answersheet()
    {
        $response = $this->client->createAnswersheet(1);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('link', $response);
        $this->assertNotEmpty($response['link']);
        $this->assertStringStartsWith(getenv('QUESTIONNAIRE_URL') . '/answersheet/' . $response['answersheet_id'], $response['link']);
    }
}
