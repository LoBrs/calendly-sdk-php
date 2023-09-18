<?php

namespace LoBrs\Calendly\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use LoBrs\Calendly\CalendlyApi;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\ApiRateLimitExceededException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

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
        $this->assertInstanceOf(ResponseInterface::class, $this->api->getLastResponse());
    }

    public function testExceptionResponse() {

        $this->expectException(ApiErrorException::class);
        $this->mockHandler->append(new Response(400));
        $this->api->request("/foo");
    }

    public function testExceptionDetails() {

        $this->mockHandler->append(new Response(400, [
            'content-type' => 'application/json; charset=utf-8',
        ], '{"title": "Invalid Argument", "message": "There is an error", "details": [{"foo":"bar"}]}'));

        try {
            $this->api->request("/foo");
        } catch (ApiErrorException $e) {
            $this->assertIsArray($e->getDetails());
            $this->assertEquals("There is an error", $e->getMessage());
            $this->assertInstanceOf(ResponseInterface::class, $this->api->getLastResponse());
        }
    }

    public function testRateLimitHeader() {

        $this->mockHandler->append(new Response(429, [
            'X-RateLimit-Limit'     => 1000,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset'     => 60,
        ]));

        try {
            $this->api->request("/foo");
        } catch (ApiErrorException $e) {
            $this->assertInstanceOf(ApiRateLimitExceededException::class, $e);
        }
        $this->assertEquals(1000, current($this->api->getLastResponse()->getHeader('X-RateLimit-Limit')));
        $this->assertEquals(0, current($this->api->getLastResponse()->getHeader('X-RateLimit-Remaining')));
        $this->assertEquals(60, current($this->api->getLastResponse()->getHeader('X-RateLimit-Reset')));
        $this->assertEmpty($this->api->getLastResponse()->getHeader('InvalidHeader'));
    }

}