<?php

namespace LoBrs\Calendly\OAuth2;

use LoBrs\Calendly\OAuth2\Provider\CalendlyOAuthProvider;

class CalendlyOAuth
{
    protected string $client_id;
    protected string $client_secret;
    protected string $redirect_uri;

    private CalendlyOAuthProvider $provider;

    public function __construct(string $client_id, string $client_secret, string $redirect_uri) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;

        $this->provider = new CalendlyOAuthProvider([
            "clientId"     => $this->client_id,
            "clientSecret" => $this->client_secret,
            "redirectUri"  => $this->redirect_uri,
        ]);
    }

    public function redirectToAuthorization() {
        header("Location: " . $this->getAuthorizationURL());
    }

    public function getAuthorizationURL(array $options = []): string {
        return $this->provider->getAuthorizationUrl($options);
    }

    public function getAuthorizationCode() {
        return $_REQUEST["code"] ?? null;
    }

    public function getAccessToken(?string $authorization_code = null) {
        $this->provider->getAccessToken('authorization_code', [
            'code'         => $authorization_code ?: $this->getAuthorizationCode(),
            'redirect_uri' => $this->redirect_uri,
        ]);
    }
}