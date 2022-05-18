<?php

namespace Calendly\Facades;

use Calendly\CalendlySDK;

/**
 * @method static \Calendly\Models\User me()
 * @method static \Calendly\Models\User getUser(string $uuid)
 * @method static \Calendly\Models\EventType[] getEventTypes(array $options)
 */
class Calendly
{
    static private ?CalendlySDK $sdk = null;
    static private string $token = "";

    public static function __callStatic($name, $arguments) {
        return call_user_func_array([self::getClient(), $name], $arguments);
    }

    public static function setToken($token): ?CalendlySDK {
        self::$token = $token;
        return self::getClient();
    }

    public static function getClient(): ?CalendlySDK {
        if (!self::$sdk) {
            if (empty(self::$token)) {
                throw new \InvalidArgumentException("Calendly token must be set");
            }
            self::$sdk = new CalendlySDK(self::$token);
        }

        return self::$sdk;
    }
}