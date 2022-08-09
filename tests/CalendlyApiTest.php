<?php

namespace LoBrs\Calendly\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use LoBrs\Calendly\CalendlyApi;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalendlyApiTest extends TestCase
{

    private CalendlyApi $api;
    private MockHandler $mockHandler;

    protected function setUp(): void {

        $this->mockHandler = new MockHandler();
        $httpClient = new Client([
            'handler' => HandlerStack::create($this->mockHandler),
        ]);

        $this->api = new CalendlyApi("secret", $httpClient);
    }

    public function testOkResponse() {

        $this->mockHandler->append(new Response(200, [
            'content-type' => 'application/json; charset=utf-8',
        ], '{"foo": "bar"}'));

        $response = $this->api->request("/foo");
        $this->assertEquals("bar", $response["foo"]);
    }

    public function testExceptionResponse() {

        $this->expectException(InvalidArgumentException::class);

        $this->mockHandler->append(new Response(400, [
            'content-type' => 'application/json; charset=utf-8',
        ], '{"title": "Invalid Argument", "message": "There is an error", "details": [{"foo":"bar"}]}'));

        $this->api->request("/foo");
        $this->assertIsArray($this->getExpectedException()->getDetails());
        $this->assertEquals("There is an error", $this->getExpectedException()->getMessage());
    }

}