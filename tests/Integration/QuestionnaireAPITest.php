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

    public function test_create_private_answer_sheet_not_enough_arguments()
    {
        $exceptionThrown = false;
        try {
            $this->client->createPrivateAnswerSheet(null, null);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(Exception::class, get_class($e));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_create_private_answer_sheet()
    {
        $response = $this->client->createPrivateAnswerSheet(1, 'cf935c02c17326a649569ef');
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('link', $response);
        $this->assertNotEmpty($response['link']);
        $this->assertStringStartsWith(getenv('QUESTIONNAIRE_URL') . '/answer-sheet', $response['link']);
    }

    public function test_get_all_private_questionnaires_more_that_100_per_page_throws_exception()
    {
        $exceptionThrown = false;
        try {
            $this->client->getAllPrivateQuestionnaires(1, 150);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(json_encode(['errors' => ['per_page' => ['validation.max.numeric']]]), $e->getMessage());
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_get_all_private_questionnaires()
    {
        $response = $this->client->getAllPrivateQuestionnaires();
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('questionnaires', $response);
    }
}
