<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Utils\PaginatedList;

trait Listable
{
    /**
     * @param array $options
     * @return static[]
     * @throws \Exception
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
     * @throws \Exception
     */
    public static function paginate(array $options = []): PaginatedList {
        $response = Calendly::getClient()->request(static::getResourceURI(), "GET", $options);
        if (isset($response["collection"])) {
            return static::pagination($response);
        }
        return new PaginatedList([]);
    }

    protected static function getResourceURI() {
        return "/" . static::$resource;
    }
}