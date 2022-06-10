<?php

namespace LoBrs\Calendly\Test;

use \LoBrs\Calendly\OAuth2\Provider\CalendlyOAuthProvider;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use LoBrs\Calendly\OAuth2\Provider\CalendlyUser;
use Mockery;
use PHPUnit\Framework\TestCase;

class CalendlyOAuthTest extends TestCase
{
    use QueryBuilderTrait;

    protected CalendlyOAuthProvider $provider;

    protected function setUp(): void {
        $this->provider = new CalendlyOAuthProvider([
            "clientId"     => "mock_client_id",
            "clientSecret" => "mock_secret",
            'redirectUri'  => 'none',
        ]);
    }

    public function testAuthorizationUrl() {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl() {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('auth.calendly.com', $uri['host']);
        $this->assertEquals('/oauth/authorize', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl() {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('auth.calendly.com', $uri['host']);
        $this->assertEquals('/oauth/token', $uri['path']);
    }

    protected function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    private function mockTokenResponse() {
        return [
            "access_token" => "mock_access_token",
            "expires_in"   => 315569260,
        ];
    }

    public function testGetAccessToken() {
        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(json_encode($this->mockTokenResponse()));
        $response->shouldReceive('getHeader')->andReturn(['Content-Type' => 'application/json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = Mockery::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);

        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNotNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
    }

    public function testUserData()
    {
        $token = Mockery::mock('League\OAuth2\Client\Token\AccessToken');
        $user = $this->provider->getResourceOwner($token);
        $this->assertNotNull($user);
        $this->assertInstanceOf(CalendlyUser::class, $user);
    }

}