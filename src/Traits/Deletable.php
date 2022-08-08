<?php

namespace LoBrs\Calendly\Traits;

use LoBrs\Calendly\Calendly;

trait Deletable
{
    public static function delete(array $options = []): bool {
        try {
            $response = Calendly::getClient()->request(static::$resource, "DELETE", $options);
            return true;
        } catch (\Exception $e) {
            //
        }

        return false;
    }
}