<?php

namespace LoBrs\Calendly;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\ApiRateLimitExceededException;
use LoBrs\Calendly\Exceptions\InternalServerErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\PermissionDeniedException;
use LoBrs\Calendly\Exceptions\ResourceNotFoundException;
use LoBrs\Calendly\Exceptions\UnauthenticatedException;
use LoBrs\Calendly\Models\EventType;
use LoBrs\Calendly\Models\Organization;
use LoBrs\Calendly\Models\User;

final class CalendlyApi
{
    const API_URL = "https://api.calendly.com";

    private Client $client;
    private ResponseInterface $response;

    /**
     * @var string Calendly `user` or `organization` token
     */
    private string $token;

    public function __construct(string $token, ?Client $httpClient = null) {
        $this->token = $token;
        if ($httpClient) {
            $this->client = $httpClient;
        } else {
            $this->client = new Client([
                "base_uri" => self::API_URL,
            ]);
        }
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
            $this->response = $this->client->request($method, $uri, $options);
        } catch (GuzzleException $e) {
            $this->response = $e->getResponse();
        }

        $data = json_decode($this->response->getBody(), true);
        $httpCode = $this->response->getStatusCode();
        if ($httpCode > 299) {
            $message = $data['message'] ?? "An error occurred";
            $details = $data['details'] ?? [];
            if (isset($data['title'])) {
                // get exception class from title
                $exceptionClassname = "LoBrs\\Calendly\\Exceptions\\" . str_replace(' ', '',
                        ucwords(str_replace(['-', '_'], ' ', $data['title']))) . "Exception";
                if (class_exists($exceptionClassname)) {
                    throw new $exceptionClassname($message, $details);
                }
            }

            if ($httpCode == 500) {
                throw new InternalServerErrorException($message, $details);
            }
            if ($httpCode == 404) {
                throw new ResourceNotFoundException($message, $details);
            }
            if ($httpCode == 403) {
                throw new PermissionDeniedException($message, $details);
            }
            if ($httpCode == 401) {
                throw new UnauthenticatedException($message, $details);
            }
            if ($httpCode == 429) {
                throw new ApiRateLimitExceededException($message, $details);
            }

            // Default API error exception
            throw new ApiErrorException($message, $details);
        }

        return $data;
    }

    /**
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function me(): ?User {
        $response = $this->request("/users/me");
        if (isset($response["resource"])) {
            return new User($response["resource"]);
        }
        return null;
    }

    /**
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getOrganization(?string $organization_uuid = null): ?Organization {
        if (empty($organization_uuid)) {
            return $this->me()->getCurrentOrganization();
        }

        return Organization::get($organization_uuid);
    }

    /**
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getUser(?string $uuid = null): ?User {
        if (empty($uuid)) {
            return $this->me();
        }

        return User::get($uuid);
    }

    /**
     * @throws ApiErrorException|InvalidArgumentException
     */
    public function getEventTypes(array $options): Utils\PaginatedList {
        return EventType::paginate($options);
    }

    public function getLastResponse(): ResponseInterface {
        return $this->response;
    }
}