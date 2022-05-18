<?php

namespace Calendly\Traits;

use Calendly\Facades\Calendly;

trait ChildListable
{
    /**
     * @param string $parent_uuid
     * @param array $options
     * @return static[]
     * @throws \Exception
     */
    public static function getList(string $parent_uuid, array $options = []) {
        $response = Calendly::getClient()->request(static::getResourceURI($parent_uuid), "GET", $options);
        if (isset($response["collection"])) {
            return static::collection($response["collection"]);
        }
        return [];
    }

    protected static function getResourceURI(string $parent_uuid) {
        return static::getParentResource() . "/" . $parent_uuid . "/" . static::$resource;
    }

    abstract static function getParentResource();
}