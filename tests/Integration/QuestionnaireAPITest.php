<?php

namespace QuestApiTest\Integration;

use Exception;
use PHPUnit_Framework_TestCase;
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

        $this->client = new QuestionnaireAPI();
    }

    public function test_say_hi()
    {
        $response = $this->client->sayHi();
        $this->assertEquals('hi', $response);
    }

    public function test_create_answer_sheet_not_enough_arguments()
    {
        $exceptionThrown = false;
        try {
            $this->client->createAnswerSheet(null);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(Exception::class, get_class($e));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_create_answer_sheet()
    {
        $response = $this->client->createAnswerSheet(1);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('link', $response);
        $this->assertNotEmpty($response['link']);
        $this->assertStringStartsWith('http://survey.dev/answer-sheet/' . $response['answersheet_id'], $response['link']);
    }

    public function test_create_answer_sheet_questionnaire_not_found()
    {
        $exceptionThrown = false;
        try {
            $this->client->createAnswerSheet(12);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(['status' => 'questionnaire[12] not found'], json_decode($e->getMessage(), true));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_get_answersheet_status_not_created()
    {
        $exceptionThrown = false;
        try {
            $this->client->getAnswersheetStatus(5465);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(Exception::class, get_class($e));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_get_answersheet_status()
    {
        $response = $this->client->createAnswerSheet(1);
        $status   = $this->client->getAnswersheetStatus($response['answersheet_id']);
        $this->assertEquals([
            'status' => 'not started yet',
        ], $status);
    }
}
