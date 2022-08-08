<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;
use LoBrs\Calendly\Utils\PaginatedList;

trait Listable
{
    /**
     * @param array $options
     * @return static[]
     * @throws ApiErrorException|InvalidArgumentException
     */
    public static function getList(array $options = []) {
        $response = Calendly::getClient()->request(static::getResourceURI(), "GET", $options);
        if (isset($response["collection"])) {
            return static::collection($response["collection"]);
        }
        return [];
    }

    /**
     * @param array $options
     * @return PaginatedList<static>
     * @throws ApiErrorException|InvalidArgumentException
     */
    public static function paginate(array $options = []): PaginatedList {
        $response = Calendly::getClient()->request(static::getResourceURI(), "GET", $options);
        if (isset($response["collection"])) {
            return static::pagination($response, $options);
        }
        return new PaginatedList([], $options);
    }

    protected static function getResourceURI() {
        return "/" . static::$resource;
    }
}