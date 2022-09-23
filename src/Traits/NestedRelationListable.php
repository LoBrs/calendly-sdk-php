<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Exceptions\ApiErrorException;
use LoBrs\Calendly\Exceptions\InvalidArgumentException;

trait NestedRelationListable
{
    /**
     * Returns models collection based on the parent resource.
     *
     * @param string $parent_uuid
     * @param array $options
     * @return static[]
     * @throws ApiErrorException|InvalidArgumentException
     */
    public static function getList(string $parent_uuid, array $options = []) {
        $response = Calendly::getClient()->request(static::getResourceURI($parent_uuid), "GET", $options);
        if (isset($response["collection"])) {
            return static::collection($response["collection"]);
        }
        return [];
    }

    /**
     * URI to use in the API call.
     *
     * @param string $parent_uuid
     * @return string
     */
    protected static function getResourceURI(string $parent_uuid): string {
        return static::getParentResource() . "/" . $parent_uuid . "/" . static::$resource;
    }

    abstract static function getParentResource();
}