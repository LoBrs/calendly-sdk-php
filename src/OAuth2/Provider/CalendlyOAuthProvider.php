<?php

namespace LoBrs\Calendly\OAuth2\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use LoBrs\Calendly\Calendly;
use Psr\Http\Message\ResponseInterface;

/**
 * @method CalendlyUser getResourceOwner(AccessToken $token)
 */
class CalendlyOAuthProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl() {
        return 'https://auth.calendly.com/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params) {
        return 'https://auth.calendly.com/oauth/token';
    }

    public function getRevokeTokenUrl(): string {
        return 'https://auth.calendly.com/oauth/revoke';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        return 'https://api.calendly.com/users/me';
    }

    protected function getDefaultScopes() {
        return null;
    }

    protected function checkResponse(ResponseInterface $response, $data) {
        if (empty($data['error'])) {
            return;
        }

        $message = $data['error']['message'] ?? "An error occurred";
        throw new IdentityProviderException($message, $data['error']['code'] ?? $response->getStatusCode(), $data);
    }

    public function getAccessToken($grant = 'authorization_code', array $options = []) {
        return parent::getAccessToken($grant, $options);
    }

    /**
     * Use this endpoint to revoke an access/refresh token.
     *
     * @param string $token The access/refresh token provided by Calendly
     * @return bool
     */
    public function revokeToken(string $token): bool {
        $options = [
            'client_id'         => $this->clientId,
            'client_secret'     => $this->clientSecret,
            'token'             => $token,
        ];
        $response = Calendly::request($this->getRevokeTokenUrl(), "POST", $options);

        return is_array($response);
    }

    protected function createResourceOwner(array $response, AccessToken $token) {
        return new CalendlyUser($response['resource'] ?? []);
    }
}