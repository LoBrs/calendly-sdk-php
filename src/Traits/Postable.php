<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;
use LoBrs\Calendly\Models\BaseModel;

trait Postable
{
    public static function create(array $options): ?BaseModel {
        $response = Calendly::getClient()->request(static::$resource, "POST", $options);
        if (isset($response["resource"])) {
            return new static($response["resource"]);
        }

        return null;
    }
}