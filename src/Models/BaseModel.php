<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InternalServerErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Exceptions\MissingCalendlyTokenException;
use LoBrs\Calendly\Utils\PaginatedList;

abstract class BaseModel
{
    static string $resource = "";

    protected array $data;

    public function __construct(array $response) {
        $this->data = $response;
    }

    public function getId(): ?string {
        return $this->getField('id', array_reverse(explode("/", $this->uri ?? ""))[0]);
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
     * @throws ApiErrorException|InvalidArgumentException|InternalServerErrorException
     * @return static
     */
    public static function get(string $uuid) {
        if (empty(static::$resource)) {
            throw new InternalServerErrorException("Resource cannot be found for this model");
        }
        if (strpos($uuid, "https://") === 0) {
            $uuid = array_reverse(explode("/", $uuid))[0];
        }
        $response = Calendly::getClient()->request("/" . static::$resource . "/$uuid");
        if (isset($response["resource"])) {
            return new static($response["resource"]);
        }
        return null;
    }

    /**
     * @param array $list
     * @return static[]
     */
    public static function collection(array $list): array {
        return array_map(function ($data) {
            return new static($data);
        }, $list);
    }

    /**
     * @param array $response
     * @param array $options
     * @return PaginatedList<static>
     */
    public static function pagination(array $response, array $options = []): PaginatedList {
        $response["collection"] = static::collection($response["collection"]);
        return new PaginatedList($response, $options, static::class);
    }
}