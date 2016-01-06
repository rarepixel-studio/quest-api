<?php

namespace QuestApiTest\Integration;

use Exception;
use PHPUnit_Framework_TestCase;
use QuestApi\HttpClient;

class HttpClientTest extends PHPUnit_Framework_TestCase
{
    public function test_say_hi()
    {
        $client   = new HttpClient();
        $response = $client->sayHi();
        $this->assertEquals('hi', $response);
    }

    public function test_create_private_answer_sheet_not_enough_arguments()
    {
        $exceptionThrown = false;
        $client          = new HttpClient();
        try {
            $client->createPrivateAnswerSheet(null, null);
        } catch (Exception $e) {
            $exceptionThrown = true;
            $this->assertEquals(Exception::class, get_class($e));
        }
        $this->assertTrue($exceptionThrown);
    }

    public function test_create_private_answer_sheet()
    {
        $client   = new HttpClient();
        $response = $client->createPrivateAnswerSheet(1, 'cf935c02c17326a649569ef');
        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['link']);
        $this->assertStringStartsWith(getenv('QUESTIONNAIRE_URL') . '/answer-sheet', $response['link']);
    }
}
