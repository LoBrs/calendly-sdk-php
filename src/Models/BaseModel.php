<?php

namespace LoBrs\Calendly\Models;

use LoBrs\Calendly\Calendly;

abstract class BaseModel
{
    static string $resource = "";

    protected array $data;

    public function __construct(array $response) {
        $this->data = $response;
    }

    public function getId(): ?string {
        return $this->getField('id', array_reverse(explode("/", $this->uri))[0]);
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

    public static function get(string $uuid) {
        if (empty(static::$resource)) {
            throw new \InvalidArgumentException("Resource cannot be found for this model");
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
     * @param array $collection
     * @return static[]
     */
    public static function collection(array $collection): array {
        return array_map(function ($data) {
            return new static($data);
        }, $collection);
    }
}