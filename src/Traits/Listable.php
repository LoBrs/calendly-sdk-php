<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;

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

    protected static function getResourceURI() {
        return "/" . static::$resource;
    }
}