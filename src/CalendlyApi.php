<?php

namespace LoBrs\Calendly;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InternalServerErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Models\EventType;
use LoBrs\Calendly\Models\Organization;
use LoBrs\Calendly\Models\User;

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

    /**
     * @throws ApiErrorException|InvalidArgumentException
     */
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
        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
        }

        $data = json_decode($response->getBody(), true);
        if ($response->getStatusCode() > 299) {
            $message = $data['message'] ?? "Une erreur est survenue";
            if (isset($data['title'])) {
                $exceptionClassname = "LoBrs\\Calendly\\Exceptions\\" . str_replace(' ', '',
                        ucwords(str_replace(['-', '_'], ' ', $data['title']))) . "Exception";
                if (class_exists($exceptionClassname)) {
                    throw new $exceptionClassname($message, $data['details'] ?? []);
                }
            }

            throw new ApiErrorException($message, $data['details'] ?? []);
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

    public function getOrganization(?string $organization_uuid = null): ?Organization {
        if (empty($organization_uuid)) {
            return $this->me()->getCurrentOrganization();
        }

        return Organization::get($organization_uuid);
    }

    public function getUser(?string $uuid = null): ?User {
        if (empty($uuid)) {
            return $this->me();
        }

        return User::get($uuid);
    }

    public function getEventTypes(array $options): Utils\PaginatedList {
        return EventType::paginate($options);
    }

}