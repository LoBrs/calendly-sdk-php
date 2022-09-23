<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InternalServerErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Utils\PaginatedList;

abstract class BaseModel
{
    /** @var string The resource name from the API */
    static string $resource = "";

    /** @var array Array of data filled automatically with the API response */
    protected array $data;

    public function __construct(array $response) {
        $this->data = $response;
    }

    public function getId(): ?string {
        return $this->getField('id', static::getIdFromUri($this->uri ?? ''));
    }

    public function toArray(): array {
        return $this->data;
    }

    protected function getField(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function __get($name) {
        return $this->getField($name);
    }

    /**
     * Get resource model from uuid.
     *
     * @return static
     * @throws ApiErrorException|InvalidArgumentException|InternalServerErrorException
     */
    public static function get(string $uuid) {
        if (empty(static::$resource)) {
            throw new InternalServerErrorException("Resource cannot be found for this model");
        }
        if (strpos($uuid, "https://") === 0) {
            $uuid = static::getIdFromUri($uuid);
        }
        $response = Calendly::getClient()->request("/" . static::$resource . "/$uuid");
        if (isset($response["resource"])) {
            return new static($response["resource"]);
        }
        return null;
    }

    /**
     * Returns a collection of the current model.
     *
     * @param array $list
     * @return static[]
     */
    public static function collection(array $list): array {
        return array_map(function ($data) {
            return new static($data);
        }, $list);
    }

    /**
     * Create a paginated list from a collection response.
     *
     * @param array $response
     * @param array $options
     * @return PaginatedList<static>
     */
    public static function pagination(array $response, array $options = []): PaginatedList {
        $response["collection"] = static::collection($response["collection"]);
        return new PaginatedList($response, $options, static::class);
    }

    /**
     * Retrieve the last part of uri, which is the uuid.
     *
     * @param string $uri
     * @return mixed|string
     */
    protected static function getIdFromUri(string $uri) {
        return array_reverse(explode("/", $uri))[0];
    }
}