<?php

namespace LoBrs\Calendly;

use LoBrs\Calendly\Models\EventType;
use LoBrs\Calendly\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

final class CalendlyApi
{
    const API_URL = "https://api.calendly.com";
    private Client $client;

    /**
     * @var string Token utilisateur Calendly
     */
    private string $token;

    public function __construct(string $token) {
        $this->token = $token;
        $this->client = new Client([
            "base_uri" => self::API_URL,
        ]);
    }

    public function request(string $uri, string $method = "GET", array $params = []) {
        $options[RequestOptions::HEADERS] = [
            "Accept"        => "application/json",
            "Authorization" => "Bearer " . $this->token,
        ];
        if (strtolower($method) == "get") {
            $options[RequestOptions::QUERY] = $params;
        } else {
            $options[RequestOptions::JSON] = $params;
        }
        $response = $this->client->request($method, $uri, $options);
        $data = json_decode($response->getBody(), true);
        if ($response->getStatusCode() > 299) {
            throw new \Exception($data['message'] ?? "Une erreur est survenue");
        }

        return $data;
    }

    public function me(): ?User {
        $response = $this->request("/users/me");
        if (isset($response["resource"])) {
            return new User($response["resource"]);
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function getUser(string $uuid): ?User {
        return User::get($uuid);
    }

    public function getEventTypes(array $options): array {
        return EventType::getList($options);
    }

}